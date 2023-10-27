<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

// use yii\helpers\Html;

$this->title = "404 Page Not Found";

use yii\helpers\Url;

?>
<div id="primary" class="site-main">

<section class="error-404 not-found">

    <div class="page_404">
        <div class="">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="col-sm-10 col-sm-offset-1  text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center ">404 </h1>
                        </div>

                        <div class="contant_box_404">
                            <h3 class="h2">
                                Page Not Found !!
                            </h3>

                            <!-- <p>the page you are looking for not avaible!</p> -->

                            <a href="<?= Url::base(true); ?>"><button class="errorBtn error-404" type="button">Go to Home</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .page-content -->
</section><!-- .error-404 -->

</div><!-- #main -->
