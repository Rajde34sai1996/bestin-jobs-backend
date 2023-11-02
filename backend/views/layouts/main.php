<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--
Template Name: Yii2 Admin Dashboard Template By Groovy
Author: Ashok Sachdev
-->
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        var baseUrl = "<?= Url::base(true);?>";
    </script>
</head>
<body class="app">
<div class="loader_class"></div>
<?php $this->beginBody() ?>
<?php 
    include('_header.php');
?>
<div class="loader_other"></div>
<div class="loaderText">
Start Scrapping Please Wait....
</div>

<div class="flex">
    <?php 
        include('_sidebar.php');
    ?>
     <!-- BEGIN: Content -->
   <div class="content">
      <!-- BEGIN: Top Bar -->
      <div class="top-bar">
         <!-- BEGIN: Breadcrumb -->
        
         <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
            
             <?php
                echo Breadcrumbs::widget([
                    'homeLink' =>[ 
                                    'label' => Yii::t('yii', 'Home'),
                                    'url' => Yii::$app->homeUrl,
                                    'template' => "{link}<i data-feather='chevron-right' class='breadcrumb__icon'></i></li>\n",
                                    'class' => "breadcrumb--active",
                                ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
            ?>
        </div>
         <!-- END: Breadcrumb -->
         <!-- BEGIN: Notifications -->
         <div class="intro-x dropdown relative mr-auto sm:mr-6 hide">
            <div class="dropdown-toggle notification notification--bullet cursor-pointer"> <i data-feather="bell" class="notification__icon"></i> </div>
            <div class="notification-content dropdown-box mt-8 absolute top-0 left-0 sm:left-auto sm:right-0 z-20 -ml-10 sm:ml-0">
               <div class="notification-content__box dropdown-box__content box">
                  <div class="notification-content__title">Notifications</div>
                  <div class="cursor-pointer relative flex items-center ">
                     <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo Url::base();?>/app-assets/images/profile-13.jpg">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
                     </div>
                     <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                           <a href="javascript:;" class="font-medium truncate mr-5">Angelina Jolie</a> 
                           <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">05:09 AM</div>
                        </div>
                        <div class="w-full truncate text-gray-600">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 20</div>
                     </div>
                  </div>
                  <div class="cursor-pointer relative flex items-center mt-5">
                     <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo Url::base();?>/app-assets/images/profile-2.jpg">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
                     </div>
                     <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                           <a href="javascript:;" class="font-medium truncate mr-5">Johnny Depp</a> 
                           <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">05:09 AM</div>
                        </div>
                        <div class="w-full truncate text-gray-600">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem </div>
                     </div>
                  </div>
                  <div class="cursor-pointer relative flex items-center mt-5">
                     <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo Url::base();?>/app-assets/images/profile-14.jpg">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
                     </div>
                     <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                           <a href="javascript:;" class="font-medium truncate mr-5">Russell Crowe</a> 
                           <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">01:10 PM</div>
                        </div>
                        <div class="w-full truncate text-gray-600">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 20</div>
                     </div>
                  </div>
                  <div class="cursor-pointer relative flex items-center mt-5">
                     <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo Url::base();?>/app-assets/images/profile-6.jpg">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
                     </div>
                     <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                           <a href="javascript:;" class="font-medium truncate mr-5">Al Pacino</a> 
                           <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">05:09 AM</div>
                        </div>
                        <div class="w-full truncate text-gray-600">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomi</div>
                     </div>
                  </div>
                  <div class="cursor-pointer relative flex items-center mt-5">
                     <div class="w-12 h-12 flex-none image-fit mr-1">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo Url::base();?>/app-assets/images/profile-5.jpg">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-full border-2 border-white"></div>
                     </div>
                     <div class="ml-2 overflow-hidden">
                        <div class="flex items-center">
                           <a href="javascript:;" class="font-medium truncate mr-5">Edward Norton</a> 
                           <div class="text-xs text-gray-500 ml-auto whitespace-no-wrap">01:10 PM</div>
                        </div>
                        <div class="w-full truncate text-gray-600">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- END: Notifications -->
         <!-- BEGIN: Account Menu -->
         <div class="intro-x dropdown w-8 h-8 relative">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
               <img alt="User Profile" src="<?= Url::base().'/../img_assets/avatar.png' ?>">
            </div>
            <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
               <div class="dropdown-box__content box bg-theme-38 text-white">
                  <div class="p-4 border-b border-theme-40">
                     <div class="font-medium"><?= isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : '' ?></div>
                     <div class="text-xs text-theme-41">Hello</div>
                  </div>
                  <div class="p-2">
                     <?php $id = (isset(Yii::$app->user->identity->id)) ? Yii::$app->user->identity->id : 0 ?>
                     <a href="<?= Url::base(true).'/settings/index'?>" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> <i data-feather="settings" class="w-4 h-4 mr-2"></i> Settings </a>
                  </div>
                  <div class="p-2 border-t border-theme-40">
                     <a href="<?= Url::to(['/site/logout'], $schema = true) ?>" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout </a>
                  </div>
               </div>
            </div>
         </div>
         <!-- END: Account Menu -->
      </div>
      <!-- END: Top Bar -->
      <?php echo $content;?>
      
           
      </div>
   </div>
   <!-- END: Content -->
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
