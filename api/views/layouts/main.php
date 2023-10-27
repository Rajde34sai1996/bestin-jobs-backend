<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);

$userId     = Yii::$app->user->id;
$controller = Yii::$app->controller->id;
$action     = Yii::$app->controller->action->id;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <?php $this->registerCsrfMetaTags() ?>

    <title>
        <?= Html::encode($this->title) . ' | YOUR_PROJECT_NAME ' . date('Y') ?>
    </title>

    <?php $this->head() ?>

    <script>
        var baseUrl = "<?= Url::base(true); ?>/";
        var _loginId = "<?= Yii::$app->user->id; ?>";
    </script>
</head>

<?php $this->beginBody() ?>

<body class="bgBody" style="overflow:hidden;">


</body>

<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>