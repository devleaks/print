<?php

use app\models\Payment;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
$dataProvider = new ActiveDataProvider([
	'query' => $model->getPayments(),
	'pagination' => false,
]);
?>
<div class="payment-index">
	<h4><?= Yii::t('store', 'Previous Payments') ?></h4>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'showPageSummary' => true,
		'layout' => '{items}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				},
				'noWrap' => true,
			],
			[
				'attribute' => 'amount',
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
	        [
				'attribute' => 'payment_method',
            	'filter' => Payment::getPaymentMethods(),
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getPaymentMethod();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
	        [
				'attribute' => 'note',
	        ],
        ],
    ]); ?>

</div>
