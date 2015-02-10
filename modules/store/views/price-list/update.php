<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PriceList */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Price List',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Composite Price Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="price-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
