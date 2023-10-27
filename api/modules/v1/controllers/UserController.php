<?php

namespace app\modules\v1\controllers;

use Yii;
use app\filters\auth\HttpBearerAuth;
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
            ]
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'test',
            'list',
            'skill-search',
            'healthcare-qualification-search',
            'send-otp',
            'verify-otp'
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
            ],
        ];

        return $behaviors;
    }

    public function actionTest()
    {

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
            $otp = 123456;
            $data = Yii::$app->request->bodyParams;
            $userOtp = Userotp::findOne(['phone_number' => $data['phone_number']]);

            if ($userOtp === null) {
                // If the phone number does not exist, create a new record
                $userOtp = new Userotp();
                $userOtp->contry_code = $data['code'];
                $userOtp->phone_number = $data['phone_number'];
                $userOtp->otp = $otp;
                if ($userOtp->save()) {
                    return ['message' => 'OTP sent successfully.', 'data' => ['otp' => $otp, 'is_old' => true]];
                } else {
                    return ['message' => Yii::$app->general->error($userOtp->errors)];
                }

            } else {
                $userOtp->otp = $otp;
                $userOtp->save();
                return ['message' => 'OTP sent successfully.', 'data' => ['otp' => $otp, 'is_old' => true]];
            }

        } catch (\Exception $e) {
            echo "/n\$e-ajay 💀<pre>";
            print_r($e);
            echo "\n</pre>";
            exit;
            return ["status" => false, 'message' => Yii::$app->general->error($userOtp->errors)];
        }
        // Send OTP to the user (implement your SMS gateway integration here)
        // Example: You can use a service like Twilio to send SMS
        // Twilio code example: 
        // $twilio->messages->create($phone_number, ['from' => $yourTwilioNumber, 'body' => 'Your OTP is: ' . $otp]);

    }


    public function actionVerifyOtp()
    {
        $data = Yii::$app->request->bodyParams;

        // Verify OTP and check if it's within the valid time window (1 minute)
        $userOtp = Userotp::findOne(['phone_number' => $data['phone_number'], 'otp' => $data['otp']]);
        $users = User::find()
        ->select(['id','name','dob','gender' ,'country','role','profile_pic','email', 'phone_number']) // Specify the columns you want to retrieve
        ->where(['phone_number' => $userOtp['phone_number']])
        ->one();
    

        if (!$userOtp || strtotime($userOtp->updated_at) < strtotime('-1 minute')) {
            Yii::$app->response->statusCode = 401; // Unauthorized status code
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => 'Invalid OTP or expired.'];
        }

        // Determine if the user is old or new
        $check_user = ($users !== null) ? 'is_old' : 'is_new';

        // Generate Authentication Token
        $secret = 'TIME_FOR_ALL_ALWAYS'; // Replace this with your actual secret key
        $tokenId = base64_encode(random_bytes(32));
        $issuedAt = time();
        $expire = $issuedAt + 3600; // Token expiration time (1 hour)

        // Token payload data
        $tokenData = [
            'iat' => $issuedAt,
            // Issued at: time when the token was generated
            'jti' => $tokenId,
            // Json Token Id: an unique identifier for the token
            'data' => ($check_user == 'is_old') ? $users : $userOtp,
            // You can add more data to the token payload if needed
        ];

        // Generate JWT token
        $authToken = JWT::encode($tokenData, $secret, 'HS256', $expire);

        return array('status' => true, 'message' => 'welcome to bestin-jobs', 'data' => ['token' => $authToken, 'check_user' => $check_user, 'users_data' => $users]);
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

}