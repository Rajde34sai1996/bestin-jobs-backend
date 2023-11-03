<?php

namespace app\modules\v1\controllers;

use app\filters\auth\HttpBearerAuth;
use common\models\Cms;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
// use lajax\translatemanager\helpers\Language as Lx;
class PageController extends ActiveController
{
    public $modelClass = 'common\models\Cms';

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
                'get-page' => ['get'],
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

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = [
            'get-page',
            'app-txt'
        ];

        return $behaviors;
    }
    public function actionAppTxt(){
        $d = \Yii::$app->general->getLangData();
        if($d){
            return [
                'success'=>true,
                'data'=> $d
            ];
        }else{
            return [
                'success'=>false,
                'message'=>'No translation for this language.'
            ];
        }
    }
    /**
     * Get Page
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionGetPage()
    {
        $slug = !empty($_GET['slug']) ? $_GET['slug'] : "";
        if($slug == ""){
            return [
                'success'=>false,
                'message'=>'Missing required parameters: slug',
                // 'message'=>['errors'=>[Lx::t('page-controller','Missing required parameters: slug')]]
            ];
        }
        $Page = Cms::find()->select(['title','app_body'])->where(['slug'=>$slug])->one();
        if($Page){
            return [
                'success'=>true,
                'data' => $Page
            ];
        }else{
            return [
                'success'=>false,
                'message'=>'Sorry, There is no any pages with this slug.'

                // 'message'=>['errors'=>[Lx::t('page-controller','Sorry, There is no any pages with this slug.')]]
            ];
        }
    }

    public function actionOptions($id = null)
    {
        return 'ok';
    }
}
