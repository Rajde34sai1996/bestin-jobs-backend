<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SkillsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CMS';
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
        <?= Html::a('Create CMS', ['create'], ['class' => 'mylink']) ?>
    
       
    </p>
    <div class="">
        <?php Pjax::begin(); ?> <!-- Start the Pjax container for the GridView -->
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
                [
                    'attribute' => 'title',
                    'filter' => Html::activeTextInput($searchModel, 'title', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'slug',
                    'filter' => Html::activeTextInput($searchModel, 'slug', ['class' => 'form-control']),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Actions',
                    'buttons' => [
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
       <?php Pjax::end(); ?> <!-- End the Pjax container for the GridView -->
    </div>


</div>