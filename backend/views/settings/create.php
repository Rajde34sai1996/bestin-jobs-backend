<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Settings $model */
echo "/n\$model-ajay 💀<pre>"; print_r($model); echo "\n</pre>";exit;

if($model->id){

    $this->title = 'Update Settings: ' . $model->id;
}else{

    $this->title = 'Create Settings';
}

$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
