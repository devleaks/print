<?php

use app\models\Document;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = Yii::t('store', 'Create Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/document/create-bid"><?= Yii::t('store', 'Enter new bid')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/document/create"><?= Yii::t('store', 'Enter new order')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/document/create-bill"><?= Yii::t('store', 'Enter new bill')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/document/create-credit"><?= Yii::t('store', 'Enter new credit note')?></a></li>
	</ul>

</div>
