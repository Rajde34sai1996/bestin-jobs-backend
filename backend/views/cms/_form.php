<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model common\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box p-5 cms-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Title *') ?>

            <?php if(!$model->id){?>
            <?= $form->field($model, 'slug')->textInput(['rows' => 6])->label('Slug *') ?>
            <?php } ?>
            <?= $form->field($model, 'app_body')->widget(\yii2mod\markdown\MarkdownEditor::class, [
                'editorOptions' => [
                    'showIcons' => ["code", "table"],
                ],
            ])->label('For Mobile Application *'); ?>

            <?= $form->field($model, 'html_body')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advanced'
            ])->label('For Website *');
            ?>

            <?= $form->field($model, 'meta_tile')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'meta_keyword')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

            <?php if(\Yii::$app->user->identity->role == 99) { ?>
                <div class="text-right mt-5">
                    <?= Html::submitButton('Save', ['class' => 'button w-24 bg-theme-1 text-white']) ?>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>