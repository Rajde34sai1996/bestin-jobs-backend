<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HealthcareQualification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="healthcare-qualification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'qualification_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'deactive' => 'Deactive', ], ['prompt' => '']) ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
