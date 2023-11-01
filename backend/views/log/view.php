<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = $model->category;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index'],'template' => "{link}<i data-feather='chevron-right' class='breadcrumb__icon'></i></li>\n"];
$this->params['breadcrumbs'][] = $this->title;
$type = ($model->category == 'api') ? 'api' : 'index';

\yii\web\YiiAsset::register($this);
?>
<h2 class="intro-y text-lg font-medium mt-10">
    <?= Html::encode($this->title) ?>
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <br>
        <p>
            <?= Html::a(Yii::t('app', '<i class="fa fa-arrow-left" aria-hidden="true"></i> Back'), [$type], ['class' => 'button text-white bg-theme-1 shadow-md mr-2']) ?>
            <button class="button text-white bg-theme-6 shadow-md mr-2 btn-danger" style="float: right;">
                <?= Html::a('Delete', ['delete', 'id' => $model->id, 'type' => $type], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
                ]) ?>
            </button>
        </p>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-10">
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <div class="intro-y box p-5 log-form">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'level',
                    'category',
                    'log_time:datetime',
                    'prefix:ntext',
                    'message:ntext',
                ],
            ]) ?>
        </div>
    </div>
</div>