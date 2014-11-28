<?php

use app\models\OrderLine;
use app\models\OrderLineSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="order-line-detail">

<?php if($order->getOrderLines()->count() > 0): ?>

	<?= $this->render('_list', [
			'dataProvider' => new ActiveDataProvider([
				'query' => $order->getOrderLines(),
			]),
			'order' => $order,
			'action_template' => '{view} {update} {delete}'
		])
	?>

<?php endif; ?>

<?php
	if(!isset($orderLine)) {
	 	$orderLine = new OrderLine();
		$orderLine->order_id = $order->id;
	}
	echo $this->render('_add', [
		'model' => $orderLine,
		'order' => $order,
		'form'	=> $form,
	])
?>

</div>
