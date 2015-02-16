<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PriceListItem */

$this->title = Yii::t('store', 'Create Price List Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Price List Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-list-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
