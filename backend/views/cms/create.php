<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cms */

$this->title = 'Create Cms';
$this->params['breadcrumbs'][] = ['label' => 'Cms', 'url' => ['index'],'template' => "{link}<i data-feather='chevron-right' class='breadcrumb__icon'></i></li>\n"];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        <?= Html::encode($this->title) ?>
    </h2>
</div>
<?= $this->render('_form', [
    'model' => $model,
]) ?>