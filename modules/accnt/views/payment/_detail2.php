<?php

use app\models\Parameter;
use app\models\Payment;
use dosamigos\grid\GroupGridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="payment-index">

    <?= GroupGridView::widget([
        'dataProvider' => $dataProvider,
		'extraRowColumns' => ['payment_method'],
		'extraRowValue' => function($model, $index, $totals) {
			return Parameter::getTextValue('paiement', $model['payment_method']).' Total:'.var_dump($totals).'.';
		},
		'extraRowTotalsValue' => function($model, $index, $totals) {
			if(!isset($totals['amount']))
				$totals['amount'] = 0;
			$totals['amount'] += $model->amount;
			return $totals;
		},
		'extraRowPosition' => GroupGridView::POS_BELOW,
        'columns' => [
	        [
				'attribute' => 'payment_method',
	        ],
	        [
				'attribute' => 'order_name',
	            'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
	                    return Html::a($model->getDocument()->one()->name, Url::to(['/order/document/view', 'id' => $model->document_id]));
	            },
	            'format' => 'raw',
	        ],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				},
			],
			[
				'attribute' => 'amount',
				'format' => 'currency',
			],
	        [
	            'attribute' => 'status',
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getStatusLabel();
	            },
	            'format' => 'raw',
	        ],
        ],
    ]); ?>

</div>
