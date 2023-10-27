<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HealthcareQualification */

$this->title = 'Create Healthcare Qualification';
$this->params['breadcrumbs'][] = ['label' => 'Healthcare Qualifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="healthcare-qualification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
