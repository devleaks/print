<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parameter */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Parameter',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Parameters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->domain, 'url' => ['index', 'ParameterSearch' => ['domain'=>$model->domain]]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'domain' => $model->domain, 'name' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="parameter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
