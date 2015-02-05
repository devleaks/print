<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PriceListItem */

$this->title = Yii::t('store', 'Update {modelClass}: ', [
    'modelClass' => 'Price List Item',
]) . ' ' . $model->item->libelle_court;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Price Lists'), 'url' => ['price-list/index']];
$this->params['breadcrumbs'][] = ['label' => $model->item->libelle_court, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('store', 'Update');
?>
<div class="price-list-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
