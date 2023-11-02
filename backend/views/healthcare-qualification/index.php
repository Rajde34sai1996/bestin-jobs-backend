<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SkillsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Qualification';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    table {
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .skills-index {

        margin-top: 3rem;
    }
    input{
        width:20rem;
    }
    a{
        display: flex;
    }
</style>
<div class="skills-index">

    <h1>
        <b>
            <?= Html::encode($this->title) ?>
        </b>
    </h1>

    <p style="display:flex;
    justify-content: space-between;">
        <?= Html::a('Create Qualification', ['create'], ['class' => 'mylink']) ?>
    

       
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); ?> <!-- Start the Pjax container for the GridView -->

    <div class="">
   
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'rowOptions' => function ($model, $key, $index, $grid) {
                        $class = $index % 2 === 0 ? 'even-row-class' : 'odd-row-class';
                        return ['class' => $class];
                    },

            'options' => ['class' => 'tab-space'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'Id',
                ],
                // 'id',
                [
                    'attribute' => 'qualification_name',
                    'filter' => Html::activeTextInput($searchModel, 'qualification_name', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $class = $model->status === 'active' ? 'active' : 'deactive';
                                    return Html::a($model->status === 'active' ? '<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Active ' : '<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Deactive', ['status', 'id' => $model['status']], [
                                        'title' => Yii::t('app', 'status'),
                                        'class' => $class,
                                    ]);
                            },
                ],
        
                // 'created_at',
                // 'updated_at',
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
                // ['class' => 'yii\grid\ActionColumn'],
            ],

            // 'filterModel' => $searchModel,
        ]); ?>
       <?php Pjax::end(); ?> <!-- End the Pjax container for the GridView -->
      
    </div>


</div>