<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'API Call';
$this->params['breadcrumbs'][] = $this->title;
?>
<h2 class="intro-y text-lg font-medium mt-10">
    <?= Html::encode($this->title) ?>
</h2>
<?= Html::a(Yii::t('app', 'Clear API call'), ['clear', 'type' => 'api'], [
    'class' => 'button text-white bg-theme-6 shadow-md mr-2',
    'style' => 'float:right',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to clear all API logs?'),
        'method' => 'post',
    ],
]) ?>
<div class="grid grid-cols-12 gap-6 mt-10">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible" style="word-wrap: anywhere;">
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class'     => 'yii\grid\ActionColumn',
                        'template'  => '<div style="display:flex">{view} {delete}</div>',
                        'header'    =>'Action',
                        'buttons'   => [
                            'view'   => function ($url, $model) {
                                return Html::a('<i data-feather="eye" class="w-4 h-4 mr-1"></i>', ['view', 'id' => $model->id, 'type' => $model->category], [
                                        'title' => Yii::t('app', 'View'),
                                        'class' => 'flex tooltip',
                                    ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i data-feather="trash-2" class="w-4 h-4 mr-1"></i>', ['delete', 'id' => $model->id, 'type' => $model->category],[
                                        'title' => Yii::t('app', 'Delete'),
                                        'class' => 'flex text-theme-6 tooltip',
                                        'data'  => [
                                            'confirm'   => Yii::t('app', 'Are you absolutely sure ? You will lose all the information about this user with this action.'),
                                            'method'    => 'post',
                                            'data-pjax' => false
                                    ]]);
                            },
                        ],
                    ],
                    // 'prefix:ntext',
                    [
                        'attribute' => 'prefix',
                        'format' => 'raw',
                        'filter' => '<input type="text" name="LogSearch[prefix]" value="' . (!empty($_GET['LogSearch']) ? $_GET['LogSearch']['prefix'] : "") . '" class="input w-full border mt-2 form-control">',
                        'value' => function ($model) {
                            return '<h3>' . $model['prefix'].'</h3>';
                        },
                    ],
                    'log_time:datetime',
                    // 'prefix:ntext',
                    [
                        'attribute' => 'message',
                        'format' => 'raw',
                        'filter' => '<input type="text" name="LogSearch[message]" value="' . (!empty($_GET['LogSearch']) ? $_GET['LogSearch']['message'] : "") . '" class="input w-full border mt-2 form-control">',
                        'value' => function ($model) {
                            return '<h3>' . $model['message'].'</h3>';
                        },
                    ],
                    // [
                    //     'label' => 'Data',
                    //     'value' => function ($model) {
                    //         return $model->message;
                    //         // return substr($model->message, 0, 180);
                    //     }
                    // ],
                    
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>