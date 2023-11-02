<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    table {
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    table thead tr {
        background-color: none;
        background: none;
    }

    table tr {
        background: #fff;
        height: 4rem;
    }

    .mylink {
        padding: 0.8rem;
        background: #4e99ff;
        color: #fff;
        border-radius: 8px;
    }

    .settings-index {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding-top: 5rem;
    }
</style>
<div class="settings-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Create Settings', ['create'], ['class' => 'mylink']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'id'
            ],
            'key',
            'value',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'buttons' => [
                    // 'status' => function ($url, $model, $key) {
                    //             $class = $model->status === 'active' ? 'active' : 'deactive';
                    //             return Html::a($model->status === 'active' ? '<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Active ' : '<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Deactive', ['status', 'id' => $model['status']], [
                    //                 'title' => Yii::t('app', 'status'),
                    //                 'class' => $class,
                    //             ]);
                    //         },
                    'update' => function ($url, $model) {
                                return Html::a('<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit ', ['update', 'id' => $model['id']], [
                                    'title' => Yii::t('app', 'Update'),
                                    'class' => 'flex tooltip',
                                ]);
                            },
                    'delete' => function ($url, $model) {
                                return Html::a('<i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete', ['delete', 'id' => $model['id']], [
                                    'title' => Yii::t('app', 'Delete'),
                                    'class' => 'flex text-theme-6 tooltip',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ]
                                ]);
                            },
                ],
                'template' => '<div style="display:flex;gap:3rem">{status}{update}{delete}</div>',
            ],  
        ],
    ]); ?>


</div>