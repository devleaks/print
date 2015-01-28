<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Master */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Master',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
