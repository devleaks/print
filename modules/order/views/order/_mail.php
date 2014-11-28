<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
$this->title = Yii::t('store', $model->order_type) . ' ' . $model->name;
?>
<div class="order-print">

	<?= $this->render('_header_print', [
			'model' => $model,
	    ])
	?>

	<?= $this->render('../order-line/_print', [
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getOrderLines()
			]),
			'order' => $model,
	    ])
	?>

	<?= $this->render('_footer_print', [
			'model' => $model,
	    ])
	?>

</div>
