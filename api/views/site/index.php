<?php

use yii\helpers\Url;

$this->title = "Best in Job";
$user_id     = \Yii::$app->user->id;

?>

<section class="mainGr">
    <div class="exploreMain AddResEx">
        <span class="error_message"><?= isset($error_message) ? $error_message : '' ?></span>
        <div class="row">
            <div class="loader">
                <img src="<?= Url::base(true); ?>/img_assets/LipAnimation.gif">
            </div>
        </div>
    </div>

    <div class="lightBox_div"></div>
</section>