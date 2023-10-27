<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

$controller_id = Yii::$app->controller->id;
$action_id  = Yii::$app->controller->action->id;
$tabs       = Yii::$app->setting->HeaderHtml($controller_id, $action_id);

?>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-12 d-flex align-items-center justify-res">
                <div class="row wid-100">
                    <div class="col-sm-3 M3-Width">
                        <a href="<?= Url::base(true); ?>" class="logoFull"><img src="<?= Yii::$app->request->baseUrl; ?>/api/web/ass/assets/img/Logo.png" alt="logo"></a>
                        <a href="<?= Url::base(true); ?>" class="logoLip"><img src="<?= Yii::$app->request->baseUrl; ?>/api/web/ass/assets/img/Lip-icon.png" alt="logo"></a>
                    </div>
                    <div class="col-sm-6 M6-Width">
                        <div class="navHeader">
                            <nav class="nav-menu d-none d-lg-block">
                                <ul>
                                    <?= $tabs ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-sm-3 text-center M3-Width">
                        <?= Html::a(
                            'Logout',
                            ['site/logout'],
                            ['class' => 'btn primary-btn LogOut-btn reslogOut', 'data-method' => 'post']
                        ); ?>
                    </div>
                </div>
                <!-- Uncomment below if you prefer to use an image logo -->

            </div>
        </div>

    </div>
</header><!-- End Header -->

<!-- Explore section -->