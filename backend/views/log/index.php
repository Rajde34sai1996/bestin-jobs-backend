<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Log;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
$filter_array = [];
$log_data = Log::find()->select('category')->where(['!=', 'category', 'api'])->groupBy('category')->asArray()->all();
foreach($log_data as $log){
    $filter_array[$log['category']] = $log['category'];
}

?>
<h2 class="intro-y text-lg font-medium mt-10">
    <?= Html::encode($this->title) ?>
</h2>
<?= Html::a(Yii::t('app', 'Clear log'), ['clear'], [
    'class' => 'button text-white bg-theme-6 shadow-md mr-2',
    'style' => 'float:right',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to clear all logs?'),
        'method' => 'post',
    ],
]) ?>
<div class="grid grid-cols-12 gap-6 mt-10">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'category',
                        'format' => 'raw',
                        'filter' => '<input type="text" name="LogSearch[category]" value="' . (!empty($_GET['LogSearch']) ? $_GET['LogSearch']['category'] : "") . '" class="input w-full border mt-2 form-control">',
                        'value' => function ($model) {
                            return '<h3>' . $model['category'].'</h3>';
                        },
                    ],
                    'log_time:datetime',
                    // 'prefix:ntext',
                    [
                        'attribute' => 'prefix',
                        'format' => 'raw',
                        'filter' => '<input type="text" name="LogSearch[prefix]" value="' . (!empty($_GET['LogSearch']) ? $_GET['LogSearch']['prefix'] : "") . '" class="input w-full border mt-2 form-control">',
                        'value' => function ($model) {
                            return '<h3>' . $model['prefix'].'</h3>';
                        },
                    ],
                    // 'message:ntext',
                    [
                        'class'     => 'yii\grid\ActionColumn',
                        'template'  => '<div style="display:flex">{view} {delete}</div>',
                        'header'    =>'Action',
                        'buttons'   => [
                            'view'   => function ($url, $model) {
                                return Html::a('<i data-feather="eye" class="w-4 h-4 mr-1"></i>', ['view', 'id' => $model->id, 'type' => 'log'], [
                                        'title' => Yii::t('app', 'View'),
                                        'class' => 'flex tooltip',
                                    ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i data-feather="trash-2" class="w-4 h-4 mr-1"></i>', ['delete', 'id' => $model->id, 'type' => 'log'],[
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
                ],
            ]); ?>
        <?php Pjax::end(); ?>

    </div>
</div>