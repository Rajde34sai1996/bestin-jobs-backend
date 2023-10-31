<?php

namespace app\modules\v1\controllers;

use Yii;
use app\filters\auth\HttpBearerAuth;
use app\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
use yii\web\HttpException;
use lajax\translatemanager\helpers\Language as Lx;
use yii\helpers\ArrayHelper;

use common\models\SkillsSearch;
use common\models\HealthcareQualificationSearch;
use Firebase\JWT\JWT;
use common\models\Userotp;
use yii\web\Response;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\User;
use common\models\Images;
use yii\web\UploadedFile;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],

        ];

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'test'

            ],
        ];

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'list' => ['get'],
                'skills-search' => ['get'],
                'healthcare-qualification-search' => ['get'],
                'send-otp' => ['post'],
                'verify-otp' => ['post'],
                'upload' => ['post'],
                'add-profile' => ['post'],
                'test' => ['post'],
            ]
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'list',
            'skill-search',
            'healthcare-qualification-search',
            'send-otp',
            'verify-otp',
            'upload',
            'add-profile'
        ];

        // setup access
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => [''],
            //only be applied to
            'rules' => [
                [
                    'allow' => true,
                    'actions' => [''],
                    'roles' => ['user'],
                ],
                [
                    'allow' => true,
                    'actions' => ['test'],
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionTest()
    {
        echo "/nYii::\$app->user->id-ajay 💀<pre>"; print_r(Yii::$app->user->id); echo "\n</pre>";exit;
    }


    public function actionList()
    {
        // Demo data for the list of items
        $items = [
            [
                'id' => 1,
                'name' => 'Item 1',
                'description' => 'Description for Item 1',
            ],
            [
                'id' => 2,
                'name' => 'Item 2',
                'description' => 'Description for Item 2',
            ],
            [
                'id' => 3,
                'name' => 'Item 3',
                'description' => 'Description for Item 3',
            ],
            // Add more items as needed
        ];

        // Return the demo data as a JSON response
        return array('status' => false, 'message' => 'Somthing went wrong!!!', 'data' => $items);
    }
    //----------------------------------------------------------------
    //Search Skill List API For Users Side Profile Create Time

    public function actionSendOtp()
    {
        // Generate OTP
        try {
            $otpdata = 123456;
            $data = Yii::$app->request->bodyParams;
            $userOtp = Userotp::findOne(['contry_code'=>$data['contry_code'],'phone_number' => $data['phone_number']]);

            if ($userOtp === null) {
                // If the phone number does not exist, create a new record
                $otp = new Userotp();
                $otp->contry_code = $data['contry_code'];
                $otp->phone_number = $data['phone_number'];
                $otp->otp = $otpdata;
                if ($otp->save()) {
                    return ['message' => 'OTP sent successfully.', 'data' => ['otp' => $otpdata, 'is_old' => false]];
                } else {
                    return ['message' => $otp->errors];
                }

            } else {
                $userOtp->otp = $otpdata;
                $userOtp->save();
                return ['message' => 'OTP sent successfully.', 'data' => ['otp' => $otpdata, 'is_old' => true]];
            }

        } catch (\Exception $e) {
            return ["status" => false, 'message' => $e];
        }
        // Send OTP to the user (implement your SMS gateway integration here)
        // Example: You can use a service like Twilio to send SMS
        // Twilio code example: 
        // $twilio->messages->create($phone_number, ['from' => $yourTwilioNumber, 'body' => 'Your OTP is: ' . $otp]);

    }


    public function actionVerifyOtp()
    {   

        try {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())){
                if ($model->validate() && $model->login()) {
                    if($model->is_new){
                        $user = $model->getUser();
                        return Yii::$app->commonuser->makelogin($user);
                    }
                    return array('status' => false, 'message' => 'otp verify', 'data' => '');
                } else {
                    return array('status' => true, 'message' => Yii::$app->general->error($model->errors));
                }
            }else {
                return array('status' => false, 'message' => 'Login Credentials Are Not Found !');
            }
        } catch (\Throwable $e) {
            Yii::$app->general->createLogFile($e);
            return array('status' => false, 'message' => $e->getMessage());
        }

        
    }

    public function actionSkillsSearch()
    {
        $searchModel = new SkillsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return array('status' => true, 'message' => 'got your skills list', 'data' => $dataProvider);
    }
    public function actionHealthcareQualificationSearch()
    {
        $searchModel = new HealthcareQualificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return array('status' => true, 'message' => 'got your healthcare-qualification-search list', 'data' => $dataProvider);
    }
    public function actionUpload()
    {
        try {
            $file = UploadedFile::getInstanceByName('file'); // 'file' is the name of the input field in the form
            $data = Yii::$app->request->bodyParams;
            $model = new Images();
            if ($file) {
                $uploadPath = Yii::$app->params['uploadPath'];
                $filename = uniqid() . '.' . $file->extension;
                $filePath = $uploadPath . $filename;
                $model->user_id = $data['user_id'];
                $model->path = $filePath;
                $model->type = $data['type'];
                $model->extention = $file->extension;
                if ($model->save()) {
                    if ($file->saveAs($filePath)) {
                        // File uploaded successfully
                        return ['success' => 200, 'data' => $filename, 'message' => 'file uploaded successfully'];
                    } else {
                        // Delete the model if file saving fails
                        $model->delete();
                        return ['success' => false, 'message' => 'Failed to save the uploaded file.'];
                    }
                } else {
                    // Model validation failed, return the validation errors
                    return ['success' => false, 'message' => $model->errors];
                }
            } else {
                // No file provided in the request
                return ['success' => false, 'error' => 'No file uploaded.'];
            }
        } catch (\Exception $e) {
            echo "/n\$e-ajay 💀<pre>";
            print_r($e);
            echo "\n</pre>";
            exit;

        }

    }
    public function actionAddProfile(){
        try{
            $data = Yii::$app->request->post();
        }catch(\Exception $e){
            return ['success' => false, 'error' => 'something is wrong try again.'];
        }
    }
}