<?php

use app\models\Parameter;
use app\models\Payment;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$buttons = Html::a(Yii::t('store', 'Delete all payments'), ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
        'method' => 'post',
    ],
]);

if(Parameter::isTrue('application', 'allow_direct_payment_update')) {
	$buttons =  Html::a(Yii::t('store', 'Edit account line'), ['update', 'id' => $model->id], [
	    'class' => 'btn btn-danger',
	]).' '.$buttons;
}


/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="payment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Yii::t('store', 'Payments').'</h3>',
			'footer' => $buttons,
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
	        [
				'attribute' => 'client_name',
	            'label' => Yii::t('store', 'Client'),
				'value' => 'client.nom',
			],
	        [
				'attribute' => 'order_name',
	            'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
						if($doc = $model->getDocument()->one())
	                    	return Html::a($doc->name, Url::to(['/order/document/view', 'id' => $doc->id]));
						else
							return null;
	            },
	            'format' => 'raw',
	        ],
			[
				'attribute' => 'created_at',
            	'label' => Yii::t('store', 'Payment Date'),
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return $model->account ? new DateTime($model->account->payment_date) : new DateTime($model->created_at);
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
			'note',
	        [
	            'attribute' => 'status',
            	'filter' => [Payment::STATUS_PAID => Yii::t('store', Payment::STATUS_PAID), Payment::STATUS_OPEN => Yii::t('store', Payment::STATUS_OPEN)],
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
