<?php

use app\models\Payment;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Payments');
$this->params['breadcrumbs'][] = $this->title;
/*
<h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Payment'), ['create'], ['class' => 'btn btn-success']) ?></h1>
*/
?>
<div class="payment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Yii::t('store', 'Payments').'</h3>',
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
	        [
	            'label' => Yii::t('store', 'Client'),
				'attribute' => 'client.nom',
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
	            'attribute' => 'status',
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

            ['class' => 'kartik\grid\ActionColumn'],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
