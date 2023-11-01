<?php

use yii\widgets\Menu;

$logo =  '';

function menuActive($menu)
{
  if (Yii::$app->controller->id == $menu) {
    return true;
  } else {
    return false;
  }
}

function submenuActive($menu, $submenu, $sub_type = '')
{
  $type = Yii::$app->request->get('type');
  if (!empty(Yii::$app->controller->module->requestedAction)) {
    if (isset($type) && $sub_type == '') {
      if (in_array(Yii::$app->controller->id, (array)$menu) && in_array(Yii::$app->controller->module->requestedAction->id, $submenu) && $sub_type == $type) {
        return true;
      }
    } else {
      if (in_array(Yii::$app->controller->id, (array)$menu) && in_array(Yii::$app->controller->module->requestedAction->id, $submenu) && empty($sub_type) || isset($type) && $type == $sub_type) {
        return true;
      } else {
        return false;
      }
    }
  }
}

function defaultmenuActive($menu)
{
  if (!empty(Yii::$app->controller->module)) {
    if (Yii::$app->controller->module->id == $menu) {
      return true;
    } else {
      return false;
    }
  }
}


?>
<!-- BEGIN: Side Menu -->
<nav class="side-nav">
  <a href="<?= Yii::$app->homeUrl ?>" class="intro-x flex items-center pl-5 pt-4">
    <img alt="MICBT" src="<?= Yii::$app->request->baseUrl . '/../img_assets/app_logo.png' ?>" style="background-color: white;padding: 15px;border-radius: 10px;" class="hidden xl:block">
  </a>
  <div class="side-nav__devider my-6"></div>

  <?= Menu::widget([
    'options' => [
      'data-menu' => '',
      'class'     => '',
      'id'        => '',
    ],
    'items' => [
      [
        'label'       => '<div class="side-menu__icon"> <i data-feather="home"></i> </div>
                              <div class="side-menu__title"> Dashboard </div>',
        'options'     => ['class' => 'has-sub nav-item'],
        'linkOptions' => ['class' => 'menu-item'],
        'url'         => ['/site/index'],
        'template'    => '<a href="{url}" class="side-menu ' . (menuActive('site') ? "side-menu--active" : "") . '">{label}</a>',
      ],
      [
        'label'           => '<div class="side-menu__icon"> <i data-feather="archive"></i> </div> <div class="side-menu__title">CMS</div>',
        'options'         => ['class' => 'has-sub nav-item'],
        'linkOptions'     => ['class' => 'menu-item'],
        'url'             => ['/cms/index'],
        'template'        => '<a href="{url}" class="side-menu ' . (menuActive('cms') ? "side-menu--active" : "") . '">{label}</a>',
      ],  [
        'label'           => '<div class="side-menu__icon"> <i data-feather="book"></i> </div> <div class="side-menu__title">Qualification List</div>',
        'options'         => ['class' => 'has-sub nav-item'],
        'linkOptions'     => ['class' => 'menu-item'],
        'url'             => ['/healthcare-qualification/index'],
        'template'        => '<a href="{url}" class="side-menu ' . (menuActive('healthcare-qualification') ? "side-menu--active" : "") . '">{label}</a>',
      ],
      [
        'label'           => '<div class="side-menu__icon"><i data-feather="help-circle"></i></div> <div class="side-menu__title"> Skills List</div>',
        'options'         => ['class' => 'has-sub nav-item'],
        'linkOptions'     => ['class' => 'menu-item'],
        'url'             => ['/skills/index'],
        'template'        => '<a href="{url}" class="side-menu ' . (menuActive('skills') ? "side-menu--active" : "") . '">{label}</a>',
      ],
      [
        'label'           => '<div class="side-menu__icon"> <i data-feather="code"></i> </div> <div class="side-menu__title"> For Developer <i data-feather="chevron-down" class="side-menu__sub-icon"></i> </div>',
        'options'         => ['class' => 'has-sub nav-item'],
        'linkOptions'     => ['class' => 'menu-item'],
        'url'             => 'javascript:;',
        'template'        => '<a href="{url}" class="side-menu ' . (menuActive('log') ? "side-menu--active" : "") . (menuActive('notification') ? "side-menu--active" : "") . '">{label}</a>',
        'submenuTemplate' => '<ul class="menu-content ' . (menuActive('log') ? "side-menu__sub-open" : "") . (menuActive('notification') ? "side-menu__sub-open" : "") . ' ">{items}</ul>',
        'items'           => [  //SubType
          [
            'label'   => '<div class="side-menu__icon"> <i data-feather="activity"></i> </div> <div class="side-menu__title"> Api Call </div>',
            'url'     => ['/log/api'],
            'options' => ['class' => 'has-sub nav-item'],
            'template' => '<a href="{url}" class="side-menu ' . (submenuActive('log', ['api', 'view'], 'api') ? "side-menu--active" : "") . '">{label}</a>',
          ],
          [
            'label'   => '<div class="side-menu__icon"> <i data-feather="activity"></i> </div> <div class="side-menu__title"> Log </div>',
            'url'     => ['/log/index'],
            'options' => ['class' => 'has-sub nav-item'],
            'template' => '<a href="{url}" class="side-menu ' . (submenuActive('log', ['index', 'view'], 'log') ? "side-menu--active" : "") . '">{label}</a>',
          ],
          // [
          //   'label'   => '<div class="side-menu__icon"> <i data-feather="activity"></i> </div> <div class="side-menu__title"> Notification Testing </div>',
          //   'url'     => ['/notification/index'],
          //   'options' => ['class' => 'has-sub nav-item'],
          //   'template' => '<a href="{url}" class="side-menu ' . (submenuActive('notification', ['index', 'create', 'view']) ? "side-menu--active" : "") . '">{label}</a>',
          // ],
        ],
      ],
      ],
    'submenuTemplate' => "\n<ul class='menu-content'>\n{items}\n</ul>\n",
    'encodeLabels' => false, //allows you to use html in labels
    'activateParents' => true,
  ]);
  ?>
</nav>
<!-- END: Side Menu -->