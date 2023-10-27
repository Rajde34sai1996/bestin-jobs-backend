<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HealthcareQualification */

$this->title = 'Update Healthcare Qualification: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Healthcare Qualifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="healthcare-qualification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
