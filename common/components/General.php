<?php

namespace common\components;

use Yii;
use common\models\Users;
use common\models\Images;
use common\models\UserBind;
use common\models\ListingPlans;
use common\models\BugReportApp;
use common\models\PurchaseDetails;
use common\models\CommonCategories;
use common\models\Dass;
use common\models\Es;
use common\models\Files;
use common\models\Iai;
use common\models\Miss;
use common\models\Mses;
use common\models\ScrapUsers;
use common\models\UserSocialConnect;

use common\models\SexualOrientation;
use common\models\Suds;
use common\models\Swls;
use lajax\translatemanager\helpers\Language as Lx;
use PDO;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class General extends \yii\base\Component {

    public function getLangData(){
        $Sql = "SELECT language_source.category,language_source.id,language_source.message FROM `language_source` WHERE `category` LIKE '%AppTxt%'";
        $data = \Yii::$app->db->createCommand($Sql)->queryAll();
        $d = [];
        foreach ($data as $v) {
            $result = explode('AppTxt_', $v['category']);
            $key    = $result[1];
            $d[$key]= Lx::t($v['category'], $v['message']);
        }
        return $d;
    }

    public function error($errors)
    {
        $error = [];
        foreach ($errors as $key => $value) {
            $error[] = current($value);
        }
        return implode(" , ", $error);
    }

    public function thousandsFormat($num){
        if ($num > 1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;
        }

        return $num;
    }
    
    /**
     * This function will create log file
     *
     * @param  mixed $errors		= Data to be print
     * @param  mixed $file_name		= File's name
     * @param  mixed $folder_name	= Folder name with sub path
     * @param  mixed $want_time		= pass false if you dont want to add time
     * @return void
     */
    public function createLogFile($errors, $file_name = 'log_', $folder_name = 'error_logs', $want_time = true)
    {
        $logpath        = \Yii::$app->basePath . '/../logs/' . $folder_name . '/';
        if (!is_dir($logpath)) {
            mkdir($logpath, 0777,true);
        }

        if ($want_time) {
            $file_name  = $file_name . date("j.n.Y") . '.log';
        }

        $remote_address = (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'localhost';

        //Something to write to txt log
        $log  = "-------START OF ERROR------" . PHP_EOL .
            "URL: " . $remote_address . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
            "Errors: " . print_r($errors, true) . PHP_EOL .
            "-------END OF ERROR------" . PHP_EOL;
        //Save string to log, use FILE_APPEND to append.
        file_put_contents($logpath . $file_name, $log, FILE_APPEND);
    }
    /**
     * This Function Checks the Current user have submited name or not.
     * true = Name is updated
     * false = Name is not updated
     * @param int|null post
     * @return string
     * @throws NotFoundHttpException
     */
    public function check_name_pending($user_id)
    {
        $user = Users::findOne($user_id);
        if (!empty($user)) {
            if (($user->firstname == null || $user->firstname == '' || $user->firstname == ' ') && ($user->lastname == ' '|| $user->lastname == null || $user->lastname == '')) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * This Function Checks the Current user have upload image or not.
     * true = Image Is Uploaded
     * false = Image Is Not Uploaded
     * @param int|null post
     * @return string
     * @throws NotFoundHttpException
     */
    public function check_image_pending($user_id)
    {
        $image = Images::find()->where(['main_image' => Images::IMAGE_MAIN])
            ->andWhere(['pre_define_id' => $user_id])
            ->andWhere(['image_type' => 'user'])
            ->one();

        return (empty($image)) ? true : false;
    }

    public function get_random_birthdate_by_age($min_age, $max_age)
    {
        $age = rand($min_age, $max_age);
        $birth_year = date("Y") - $age; // Current year - age
        return date("Y-m-d", strtotime("01.01.{$birth_year} +" . rand(0, 365) . " days"));
    }

    function show_limited_text($x, $length)
    {
        if (strlen($x) <= $length) {
            return $x;
        } else {
            $y = substr($x, 0, $length) . '...';
            return $y;
        }
    }

    public function reportBug()
    {
        $model = new BugReportApp;
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id   = \Yii::$app->user->id;
            if ($model->validate() && $model->save()) {
                if ($model->type == 'bug') {
                    return ['status' => true, 'message' => 'Report Sent.'];
                } else {
                    return ['status' => true, 'message' => 'Thank you for your feedback.'];
                }
            }
        }
        return ['status' => false, 'message' => $this->error($model->errors)];
    }
    
    public function set_random_radius_location($latitude, $longitude, $only_latlng = false, $max_range = 5)
    {
        $radius     = rand(1, $max_range); // in miles
        $lat_min    = number_format($latitude - ($radius / 69), 8);
        $lat_max    = number_format($latitude + ($radius / 69), 8);
        $lng_min    = number_format($longitude - $radius / abs(cos(deg2rad($latitude)) * 69), 8);
        $lng_max    = number_format($longitude + $radius / abs(cos(deg2rad($latitude)) * 69), 8);

        if($only_latlng){
            return ['lat_min' => $lat_min, 'lat_max' => $lat_max, 'lng_min' => $lng_min, 'lng_max' => $lng_max];
        }

        $lat_val    = $this->random_float($lat_min, $lat_max);
        $lng_val    = $this->random_float($lng_min, $lng_max);
        $address    = $this->geolocationaddress($lat_val, $lng_val);
        $distance   = $this->count_distance($latitude, $longitude, $lat_val, $lng_val, '');

        return ['lat' => $lat_val, 'lng' => $lng_val, 'address' => $address, 'distance' => $distance];
    }

    function random_float($one_number = 0, $two_number = 1, $mul = 1000000)
    {
        return mt_rand($one_number * $mul, $two_number * $mul) / $mul;
    }

    /**
     * Find address using lat long
     */
    public static function geolocationaddress($lat, $long)
    {
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyCoOJ_2-o8EhcAbkdI5WnP9I5-wHbPuceU";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $dataarray = json_decode($response, true);
        $location = "";
        if (count($dataarray['results']) != 0) {
            $city = '';
            $state = '';
            foreach ($dataarray['results'][0]['address_components'] as $addressComponent) {
                if (in_array('locality', $addressComponent['types'])) {
                    $city = $addressComponent['short_name'];
                }
                if ($city == '') {
                    if (in_array('administrative_area_level_2', $addressComponent['types'])) {
                        $city = $addressComponent['short_name'];
                    }
                }
                if (in_array('administrative_area_level_1', $addressComponent['types'])) {
                    $state = $addressComponent['short_name'];
                }
            }
            $location = $city . ', ' . $state;
        }

        return $location;
    }

    function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'font/otf'                                                                  => 'otf',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'font/ttf'                                                                  => 'ttf',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'image/webp'                                                                => 'webp',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'font/woff'                                                                 => 'woff',
            'font/woff2'                                                                => 'woff2',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }

    public function SendEmail($email, $UserName, $Message){  //--------> Send Email Create For Send Password 

        $message = \Yii::$app->mailer->compose()
        ->setTo($email)
        ->setfrom(['raj19@groovyweb.co'=>'MICBT'])      
        ->setSubject('Your New Password')
        ->setTextBody('Your New Password')
        ->setHtmlBody('<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2"> 
                            <div style="margin:50px auto;width:70%;padding:20px 0">
                                <div style="border-bottom:1px solid #eee">
                                    <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">
                                        <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">
                                            M<span style="color: #F28745;">i</span>cbt
                                        </a>
                                    </a>
                                </div>

                                <p style="font-size:1.1em">Hi,'. $UserName .'</p>
                                <p style="font-size:1.1em"><span>Account :</span>'.$email.'</p>
                                <p>Thank you for choosing Micbt. Your Micbt account password has been changed.</p>
                                <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$Message.'</h2>
                                <p style="font-size:0.9em;">Regards,<br />Micbt</p>
                                <hr style="border:none;border-top:1px solid #eee" />

                                <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                                    <p>Micbt</p>
                                    <p>This email is automated. Please do not reply.</p>
                                </div>
                                
                            </div>
                        </div>');      
        if ($message->send()) { 
            return true;
        } else { 
            return false;
        }
    }

    function randomPassword($upper = 1, $lower = 3, $numeric = 3, $other = 1) {   //----> Create New Passowrd 

        $pass_order = Array(); 
        $passWord = ''; 
        $special_characters = ['*','%','$','#','@','!','&','^'];

        //Create contents of the password 
        for ($i = 0; $i < $upper; $i++) { 
            $pass_order[] = chr(rand(65, 90)); 
        } 
        for ($i = 0; $i < $lower; $i++) { 
            $pass_order[] = chr(rand(97, 122)); 
        } 
        for ($i = 0; $i < $numeric; $i++) { 
            $pass_order[] = chr(rand(48, 57)); 
        } 
        for ($i = 0; $i < $other; $i++) { 
            $special_characters_kry = array_rand($special_characters,1); 
            $pass_order[] = $special_characters[$special_characters_kry]; 
        } 
        foreach ($pass_order as $char) { 
            $passWord .= $char; 
        } 
        return $passWord; 
    } 

    function UserSocialConnect($user_id, $Provider, $email, $name, $id, $response) //----> UserSocialConnect
    {   

        $checkSocial = UserSocialConnect::findOne(["provider" => $Provider, 'user_id' => $user_id]);
        if (empty($checkSocial)) {
            $connectData = new UserSocialConnect();
            $connectData->user_id       = $user_id;
            $connectData->provider      = $Provider;
            $connectData->email         = $email;
            $connectData->name          = $name;
            $connectData->provider_id   = $id;
            $connectData->date_raw      = json_encode($response);
            if ($connectData->save()) {
                return true;
            } else {
                return [
                    'status'    => false,
                    "message" => "Somthing went wrong!",
                    "data" => ["errors" => json_encode($connectData->errors)],
                ];
            }
        } else {
            return true;
        }
    }
    function dass21ChartData($user_id) //----------get Data for Chart (DASS---21)
    {   
        $model              = Dass::find()->select('depression,anxiety,stress,created_at,update_at')->where(['user_id' => $user_id])->asArray()->all();
        
        $Depression         =   array("title" => "Depression","normal"=>array(0,4),"mild"=>array(5,6),"moderate"=> array(7,10),"severe"=>array(11,13),"extreme"=>array(14,"+"));
        $Anxiety            =   array("title" =>"Anxiety","normal" =>array(0,3),"mild" =>array(4,5),"moderate" =>array(6,7),"severe" =>array(8,9),"extreme" =>array(10,"+"));
        $Stress             =   array( "title"=> "Stress","normal"=>array(0,7),"mild"=> array(8,9),"moderate"=> array(10,12),"severe"=> array(13,16),"extreme"=> array(17,"+"));
        
        $labels= [];
        $data_depression = [];
        $data_anxietyn = [];
        $data_stress = [];
        if (!empty($model)) {
            foreach($model as $key){
                array_push($labels,date('d/m/Y',$key['update_at']));
                if(isset($key['depression'])){
                    array_push($data_depression,$key['depression']);
                    $array_depression =  array(
                                        "labels"=>$labels,
                                        "datasets" => array(
                                            array(
                                                "data"    =>  $data_depression
                                            ),array(
                                                "data"    =>  array(21),
                                                "withDots"=>  false
                                            )
                                            
                                        )
                                    
                                );
                }
                if(isset($key['anxiety'])){
                    array_push($data_anxietyn,$key['anxiety']);
                    $array_anxiety =  array(
                                        "labels"=>$labels,
                                        "datasets" => array(
                                            array(
                                                "data"    =>  $data_anxietyn
                                            ),array(
                                                "data"     =>  array(21),
                                                "withDots" =>  false
                                            )
                                            
                                        )
                                    
                                );
                }
                if(isset($key['stress'])){
                    array_push($data_stress,$key['stress']);
                    $array_stress =  array(
                                        "labels"=>$labels,
                                        "datasets" => array(
                                            array(
                                                "data"    =>  $data_stress
                                            ),array(
                                                "data"    =>  array(21),
                                                "withDots"=>  false
                                            )
                                            
                                        )
                                    
                                );
                }

            }
            $Depression['chartData'] = $array_depression;
            $Anxiety['chartData']    = $array_anxiety;
            $Stress['chartData']     = $array_stress;
            $New_array = [];
            array_push($New_array,$Depression);
            array_push($New_array,$Anxiety);
            array_push($New_array,$Stress);
            return $New_array;
        } else {
            return false;
        }
    }

    function assessmentdata($user_id) //---------- assessment data  for Display main screen 
    { 
        $Dass              = Dass::find()->where(['user_id' => $user_id])->count();
        $Swls              = Swls::find()->where(['user_id' => $user_id])->count();
        $Mses              = Mses::find()->where(['user_id' => $user_id])->count();
        $Es                = Es::find()->where(['user_id' => $user_id])->count();
        $Miss              = Miss::find()->where(['user_id' => $user_id])->count();
        $Suds              = Suds::find()->where(['user_id' => $user_id])->count();
        $iai               = Iai::find()->where(['user_id' => $user_id])->count();
        $DataArray         =
            array(
                array(
                    "id" => 1,
                    "title" => "Questionnaires",
                    "data" => array(
                        array(
                            "id"        => 1,
                            "title"     => "Depression Anxiety Stress Scale-21",
                            "name"      => "(DASS-21)",
                            "count"     => (int)$Dass,
                            "iconName"  => "clipboard-list",
                            "route"     => "DASS21"
                        ),
                        array(
                            "id"        => 2,
                            "title"     => "Equanimity Scale-16",
                            "name"      => "(ES-16)",
                            "count"     => (int)$Es,
                            "iconName"  => "clipboard-list",
                            "route"     => "ES_List"
                        ),
                        array(
                            "id"        => 3,
                            "title"     => "Mindfulness based Self-Efficacy Scale",
                            "name"      => "(MSES-R)",
                            "count"     => (int)$Mses,
                            "iconName"  => "clipboard-list",
                            "route"     => "MSESR"
                        ),
                        array(

                            "id"        => 4,
                            "title"     => "Satisfaction With Life Scale",
                            "name"      => "(SWLS)",
                            "count"     => (int)$Swls,
                            "iconName"  => "clipboard-list",
                            "route"     => "Swls_List"
                        )
                    )
                ),
                array(
                    "id" => 2,
                    "title" => "Measurement Tools",
                    "data" => array(
                        array(
                            "id" => 1,
                            "title"     => "Interoceptive Awareness Indicator",
                            "name"      => "(IAI)",
                            "count"     => (int)$iai,
                            "iconName"  => "account",
                            "route"     => "IAI_List"
                        ),
                        array(
                            "id"        => 2,
                            "title"     => "Mindfulness-based Interoceptive Signature Scale",
                            "name"      => "(MISS)",
                            "iconName"  => "tune",
                            "count"     => (int)$Miss,
                            "route"     => "Miss_List"
                        ),
                        array(
                            "id"        => 3,
                            "title"     => "Subjective Units of Distress Scale",
                            "name"      => "(SUDS)",
                            "iconName"  => "percent",
                            "count"     => (int)$Suds,
                            "route"     => "SUDS_List"
                        ),
                    )
                )
            );
            return $DataArray;

    }
    function ES16ChartData($user_id) //----------get Data for Chart (ES----16)
    {
        $model_Es      = Es::find()->select('exp_acp_score,non_rea_score,update_at')->where(['user_id' => $user_id])->asArray()->all();
        $exp_acp_score = [];
        $non_rea_score = [];
        if (!empty($model_Es)) {
            foreach ($model_Es as $key) {
                $date = date('d/m/Y',$key['update_at']);
                if(isset($key['exp_acp_score'])){
                   array_push($exp_acp_score,array("x"=>$date,"y" =>(int)$key['exp_acp_score'])) ;
                }
                if(isset($key['non_rea_score'])){
                   array_push($non_rea_score,array("x"=>$date,"y" =>(int)$key['non_rea_score'])) ;
                }
            }
        }
        $ES_16_Array  =  
            [ 
                [
                    "seriesName" => "Experiential Acceptance",
                    "data" =>   $exp_acp_score,
                    "color" => '#3E5B6C',

                ],
                [
                    "seriesName" => "Non-Reactivity",
                    "data" => $non_rea_score,
                    "color" => '#F38846'

                ],
            ];
        return $ES_16_Array;
    }
    
    /**
     * ConvertToTimzone Start
     *
     * @param  mixed $date          - 2021-12-01 04:57 pm
     * @param  mixed $from_tz       - +05:30 (Current User Country Timezone)
     * @param  mixed $to_tz         - +00:00 (Current System Timezone)
     * @return void
     */
    public function ConvertToTimzone($date,$from_tz,$to_tz)
    {
        $connection = Yii::$app->getDb();
        $convert_tz = 'CONVERT_TZ ("'.$date.'","'.$from_tz.'","'.$to_tz.'")';
        $query = "SELECT ".$convert_tz;
        $command = $connection->createCommand($query);

        $result = $command->queryOne();

        return $result[$convert_tz];
    }
    // ConvertToTimzone End


    function AllStageUserData(){

      $stage_user_data = array(
        "stage_1_data" => array(
            "main_stage_1_active" => true,
            "main_stage_1_completed" => false,

            "stage_1_progress" => 0,
            "stage_1_active" => true,
            "stage_1_completed" => false,

            "stages_1_stageDetails_1_departments_1_count" => 0,
            "stages_1_stageDetails_1_departments_1_completed" => false,
            "stages_1_stageDetails_1_departments_1_disable" => false,


            "stages_1_stageDetails_1_departments_2_count" => 0,
            "stages_1_stageDetails_1_departments_2_completed" => false,
            "stages_1_stageDetails_1_departments_2_disable" => true,

            "stages_1_stageDetails_1_departments_3_count" => 0,
            "stages_1_stageDetails_1_departments_3_completed" => false,
            "stages_1_stageDetails_1_departments_3_disable" => true,

            "stages_1_stageDetails_1_departments_4_count" => 0,
            "stages_1_stageDetails_1_departments_4_completed" => false,
            "stages_1_stageDetails_1_departments_4_disable" => true,

            "stages_1_stageDetails_1_departments_5_count" => 0,
            "stages_1_stageDetails_1_departments_5_completed" => false,
            "stages_1_stageDetails_1_departments_5_disable" => true,

            "stages_1_stageDetails_1_departments_6_count" => 0,
            "stages_1_stageDetails_1_departments_6_completed" => false,
            "stages_1_stageDetails_1_departments_6_disable" => true,

            "stages_1_stageDetails_2_departments_1_count" => 0,
            "stages_1_stageDetails_2_departments_1_completed" => false,
            "stages_1_stageDetails_2_departments_1_disable" => false,

            "stages_1_stageDetails_2_departments_2_count" => 0,
            "stages_1_stageDetails_2_departments_2_completed" => false,
            "stages_1_stageDetails_2_departments_2_disable" => true,

            "stages_1_stageDetails_2_departments_3_count" => 0,
            "stages_1_stageDetails_2_departments_3_completed" => false,
            "stages_1_stageDetails_2_departments_3_disable" => true,

            "stages_1_stageDetails_2_departments_4_count" => 0,
            "stages_1_stageDetails_2_departments_4_completed" => false,
            "stages_1_stageDetails_2_departments_4_disable" => true,


            "stage_2_progress" => 0,
            "stage_2_active" => false,
            "stage_2_completed" => false,


            "stages_2_stageDetails_1_departments_1_count" => 0,
            "stages_2_stageDetails_1_departments_1_completed" => false,
            "stages_2_stageDetails_1_departments_1_disable" => false,

            "stages_2_stageDetails_1_departments_2_count" => 0,
            "stages_2_stageDetails_1_departments_2_completed" => false,
            "stages_2_stageDetails_1_departments_2_disable" => true,

            "stages_2_stageDetails_1_departments_3_count" => 0,
            "stages_2_stageDetails_1_departments_3_completed" => false,
            "stages_2_stageDetails_1_departments_3_disable" => true,

            "stages_2_stageDetails_2_departments_1_count" => 0,
            "stages_2_stageDetails_2_departments_1_completed" => false,
            "stages_2_stageDetails_2_departments_1_disable" => false,

            "stages_2_stageDetails_2_departments_2_count" => 0,
            "stages_2_stageDetails_2_departments_2_completed" => false,
            "stages_2_stageDetails_2_departments_2_disable" => true,

            "stage_3_progress" => 0,
            "stage_3_active" => false,
            "stage_3_completed" => false,


            "stages_3_stageDetails_1_departments_1_count" => 0,
            "stages_3_stageDetails_1_departments_1_completed" => false,
            "stages_3_stageDetails_1_departments_1_disable" => false,

            "stages_3_stageDetails_2_departments_1_count" => 0,
            "stages_3_stageDetails_2_departments_1_completed" => false,
            "stages_3_stageDetails_2_departments_1_disable" => false,

            "stages_3_stageDetails_2_departments_2_count" => 0,
            "stages_3_stageDetails_2_departments_2_completed" => false,
            "stages_3_stageDetails_2_departments_2_disable" => true,

            // "stages_3_stageDetails_3_departments_1_count" => 0,
            // "stages_3_stageDetails_3_departments_1_completed" => false,
            // "stages_3_stageDetails_3_departments_1_disable" => false,


            "stage_4_progress" => 0,
            "stage_4_active" => false,
            "stage_4_completed" => false,


            "stages_4_stageDetails_1_departments_1_count" => 0,
            "stages_4_stageDetails_1_departments_1_completed" => false,
            "stages_4_stageDetails_1_departments_1_disable" => false,

            "stages_4_stageDetails_2_departments_1_count" => 0,
            "stages_4_stageDetails_2_departments_1_completed" => false,
            "stages_4_stageDetails_2_departments_1_disable" => false,

            "stages_4_stageDetails_2_departments_2_count" => 0,
            "stages_4_stageDetails_2_departments_2_completed" => false,
            "stages_4_stageDetails_2_departments_2_disable" => true,

            "stages_4_stageDetails_2_departments_3_count" => 0,
            "stages_4_stageDetails_2_departments_3_completed" => false,
            "stages_4_stageDetails_2_departments_3_disable" => true,

            "stages_4_stageDetails_2_departments_4_count" => 0,
            "stages_4_stageDetails_2_departments_4_completed" => false,
            "stages_4_stageDetails_2_departments_4_disable" => true,

        ),

        "stage_2_data" => array(
            "main_stage_2_active" => false,
            "main_stage_2_completed" => false,

            "stage_1_progress" => 0,
            "stage_1_active" => false,
            "stage_1_completed" => false,

            "stages_1_stageDetails_1_departments_1_count" => 0,
            "stages_1_stageDetails_1_departments_1_completed" => false,
            "stages_1_stageDetails_1_departments_1_disable" => false,


            "stages_1_stageDetails_1_departments_2_count" => 0,
            "stages_1_stageDetails_1_departments_2_completed" => false,
            "stages_1_stageDetails_1_departments_2_disable" => true,

            "stages_1_stageDetails_1_departments_3_count" => 0,
            "stages_1_stageDetails_1_departments_3_completed" => false,
            "stages_1_stageDetails_1_departments_3_disable" => true,

            "stages_1_stageDetails_1_departments_4_count" => 0,
            "stages_1_stageDetails_1_departments_4_completed" => false,
            "stages_1_stageDetails_1_departments_4_disable" => true,

            "stages_1_stageDetails_1_departments_5_count" => 0,
            "stages_1_stageDetails_1_departments_5_completed" => false,
            "stages_1_stageDetails_1_departments_5_disable" => true,


            "stages_1_stageDetails_2_departments_1_count" => 0,
            "stages_1_stageDetails_2_departments_1_completed" => false,
            "stages_1_stageDetails_2_departments_1_disable" => false,

            "stages_1_stageDetails_2_departments_2_count" => 0,
            "stages_1_stageDetails_2_departments_2_completed" => false,
            "stages_1_stageDetails_2_departments_2_disable" => true,

            "stages_1_stageDetails_2_departments_3_count" => 0,
            "stages_1_stageDetails_2_departments_3_completed" => false,
            "stages_1_stageDetails_2_departments_3_disable" => true,

            "stages_1_stageDetails_2_departments_4_count" => 0,
            "stages_1_stageDetails_2_departments_4_completed" => false,
            "stages_1_stageDetails_2_departments_4_disable" => true,

            "stage_2_progress" => 0,
            "stage_2_active" => false,
            "stage_2_completed" => false,

            "stages_2_stageDetails_1_departments_1_count" => 0,
            "stages_2_stageDetails_1_departments_1_completed" => false,
            "stages_2_stageDetails_1_departments_1_disable" => false,

            "stages_2_stageDetails_1_departments_2_count" => 0,
            "stages_2_stageDetails_1_departments_2_completed" => false,
            "stages_2_stageDetails_1_departments_2_disable" => true,

            "stages_2_stageDetails_1_departments_3_count" => 0,
            "stages_2_stageDetails_1_departments_3_completed" => false,
            "stages_2_stageDetails_1_departments_3_disable" => true,

            "stages_2_stageDetails_1_departments_4_count" => 0,
            "stages_2_stageDetails_1_departments_4_completed" => false,
            "stages_2_stageDetails_1_departments_4_disable" => true,



            "stages_2_stageDetails_2_departments_1_count" => 0,
            "stages_2_stageDetails_2_departments_1_completed" => false,
            "stages_2_stageDetails_2_departments_1_disable" => false,

            "stages_2_stageDetails_2_departments_2_count" => 0,
            "stages_2_stageDetails_2_departments_2_completed" => false,
            "stages_2_stageDetails_2_departments_2_disable" => true,

            "stages_2_stageDetails_2_departments_3_count" => 0,
            "stages_2_stageDetails_2_departments_3_completed" => false,
            "stages_2_stageDetails_2_departments_3_disable" => true,

            "stages_2_stageDetails_2_departments_4_count" => 0,
            "stages_2_stageDetails_2_departments_4_completed" => false,
            "stages_2_stageDetails_2_departments_4_disable" => true,


            // "stages_2_stageDetails_3_departments_1_count" => 0,
            // "stages_2_stageDetails_3_departments_1_completed" => false,
            // "stages_2_stageDetails_3_departments_1_disable" => false,

            // "stages_2_stageDetails_3_departments_2_count" => 0,
            // "stages_2_stageDetails_3_departments_2_completed" => false,
            // "stages_2_stageDetails_3_departments_2_disable" => true,

        ),

        "stage_3_data" => array(
            "main_stage_3_active" => false,
            "main_stage_3_completed" => false,
            
            "stage_1_progress" => 0,
            "stage_1_active" => false,
            "stage_1_completed" => false,

            "stages_1_stageDetails_1_departments_1_count" => 0,
            "stages_1_stageDetails_1_departments_1_completed" => false,
            "stages_1_stageDetails_1_departments_1_disable" => false,

            "stages_1_stageDetails_1_departments_2_count" => 0,
            "stages_1_stageDetails_1_departments_2_completed" => false,
            "stages_1_stageDetails_1_departments_2_disable" => true,

            "stages_1_stageDetails_1_departments_3_count" => 0,
            "stages_1_stageDetails_1_departments_3_completed" => false,
            "stages_1_stageDetails_1_departments_3_disable" => true,

            "stages_1_stageDetails_1_departments_4_count" => 0,
            "stages_1_stageDetails_1_departments_4_completed" => false,
            "stages_1_stageDetails_1_departments_4_disable" => true,

            "stages_1_stageDetails_2_departments_1_count" => 0,
            "stages_1_stageDetails_2_departments_1_completed" => false,
            "stages_1_stageDetails_2_departments_1_disable" => false,

            "stages_1_stageDetails_2_departments_2_count" => 0,
            "stages_1_stageDetails_2_departments_2_completed" => false,
            "stages_1_stageDetails_2_departments_2_disable" => true,


            "stage_2_progress" => 0,
            "stage_2_active" => false,
            "stage_2_completed" => false,

                "stages_2_stageDetails_1_departments_1_count" => 0,
                "stages_2_stageDetails_1_departments_1_completed" => false,
                "stages_2_stageDetails_1_departments_1_disable" => false,

                "stages_2_stageDetails_1_departments_2_count" => 0,
                "stages_2_stageDetails_1_departments_2_completed" => false,
                "stages_2_stageDetails_1_departments_2_disable" => true,

                "stages_2_stageDetails_2_departments_1_count" => 0,
                "stages_2_stageDetails_2_departments_1_completed" => false,
                "stages_2_stageDetails_2_departments_1_disable" => false,

                "stages_2_stageDetails_2_departments_2_count" => 0,
                "stages_2_stageDetails_2_departments_2_completed" => false,
                "stages_2_stageDetails_2_departments_2_disable" => true,
        ),

        "stage_4_data" => array(
            "main_stage_4_active" => false,
            "main_stage_4_completed" => false,
            
            "stage_1_progress" => 0,
            "stage_1_active" => false,
            "stage_1_completed" => false,

                "stages_1_stageDetails_1_departments_1_count" => 0,
                "stages_1_stageDetails_1_departments_1_completed" => false,
                "stages_1_stageDetails_1_departments_1_disable" => false,

                "stages_1_stageDetails_1_departments_2_count" => 0,
                "stages_1_stageDetails_1_departments_2_completed" => false,
                "stages_1_stageDetails_1_departments_2_disable" => true,

                "stages_1_stageDetails_1_departments_3_count" => 0,
                "stages_1_stageDetails_1_departments_3_completed" => false,
                "stages_1_stageDetails_1_departments_3_disable" => true,


                "stages_1_stageDetails_2_departments_1_count" => 0,
                "stages_1_stageDetails_2_departments_1_completed" => false,
                "stages_1_stageDetails_2_departments_1_disable" => false,

                "stages_1_stageDetails_2_departments_2_count" => 0,
                "stages_1_stageDetails_2_departments_2_completed" => false,
                "stages_1_stageDetails_2_departments_2_disable" => true,

                "stages_1_stageDetails_2_departments_3_count" => 0,
                "stages_1_stageDetails_2_departments_3_completed" => false,
                "stages_1_stageDetails_2_departments_3_disable" => true,

                // "stages_1_stageDetails_3_departments_2_count" => 0,
                // "stages_1_stageDetails_3_departments_2_completed" => false,
                // "stages_1_stageDetails_3_departments_2_disable" => true,


            "stage_2_progress" => 0,
            "stage_2_active" => false,
            "stage_2_completed" => false,

                "stages_2_stageDetails_1_departments_1_count" => 0,
                "stages_2_stageDetails_1_departments_1_completed" => false,
                "stages_2_stageDetails_1_departments_1_disable" => false,

                "stages_2_stageDetails_1_departments_2_count" => 0,
                "stages_2_stageDetails_1_departments_2_completed" => false,
                "stages_2_stageDetails_1_departments_2_disable" => true,

                "stages_2_stageDetails_2_departments_1_count" => 0,
                "stages_2_stageDetails_2_departments_1_completed" => false,
                "stages_2_stageDetails_2_departments_1_disable" => false,

                // "stages_2_stageDetails_3_departments_1_count" => 0,
                // "stages_2_stageDetails_3_departments_1_completed" => false,
                // "stages_2_stageDetails_3_departments_1_disable" => false,

                // "stages_2_stageDetails_3_departments_2_count" => 0,
                // "stages_2_stageDetails_3_departments_2_completed" => false,
                // "stages_2_stageDetails_3_departments_2_disable" => true,
        )
    );

        return json_encode($stage_user_data);
    }

    function GetAllSound()
    {

        $files_data = Files::find()->all();

        $get_file_data = [];
        $data2 = "";
        foreach ($files_data as $value) {
          $data2 = [
            "id" => $value->id,
            "link" => Url::base($_SERVER['REQUEST_SCHEME']).'../backend'.$value->files_link,
            "audio" => $value->files_name,
            "duration" => $value->duration
          ];
          array_push($get_file_data,$data2);
        }

        return $get_file_data;
    }

    function GetModelData($model){

        $user_id = Yii::$app->user->identity->id;
        
        $model = $model::find()->select('*')->where(['user_id' => $user_id])->orderBy(['id' => SORT_DESC])->asArray();   

        if (!empty($model)) {
            
            $provider               = new ActiveDataProvider([
                'query'             => $model
            ]);
    
            $relationShips           =   $provider->getModels();
            $data = [];
            if (!empty($relationShips)) {
                $data = array('rows' => $relationShips);
            }else{
                $data = array('rows' => []);
            }
            return $data;
        }

    }
    public function getMinute($exp_time)
    {

        // Get current timestamp
        $currentTimestamp = time();

        // Calculate the difference in seconds between the current time and the provided timestamp
        $secondsDifference = $currentTimestamp - $exp_time;

        // Convert seconds to minutes
        $minutesDifference = round($secondsDifference / 60);
        return $minutesDifference;
    }
}