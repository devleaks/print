<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PriceListItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Price List Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-list-item-view">

	<?= $content ?>

	<?= Html::a('<span class="glyphicon glyphicon-print"></span> '.Yii::t('store', 'Print'), Url::to(['print', 'id' => $model->id]), ['class' => 'btn btn-primary', 'target' => '_blank']) ?>

</div>
