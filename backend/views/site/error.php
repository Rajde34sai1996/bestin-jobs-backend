<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="error-page flex flex-col lg:flex-row items-center justify-center h-screen text-center lg:text-left">
    <div class="-intro-x lg:mr-20">
        <img alt="Midone Tailwind HTML Admin Template" class="h-48 lg:h-auto" src="<?= Yii::$app->request->baseUrl; ?>/app-assets/images/error-illustration.svg">
    </div>
    <div class="text-white mt-10 lg:mt-0">
        <div class="intro-x text-6xl font-medium"><?= Html::encode($this->title) ?></div>
        <div class="intro-x text-xl lg:text-3xl font-medium"><?= nl2br(Html::encode($message)) ?></div> <!-- Oops. This page has gone missing. -->
        <div class="intro-x text-lg mt-3">You may have mistyped the address or the page may have moved.</div>
        <a href="<?= Yii::$app->request->baseUrl; ?>"><button class="intro-x button button--lg border border-white mt-10">Back to Home</button></a>
    </div>
</div>