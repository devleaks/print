<?php

use app\models\Parameter;
use app\models\Payment;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$template = Parameter::isTrue('application', 'allow_direct_payment_update') ? '{view} {update} {delete} {account}' : '{view} {delete}';

$this->title = Yii::t('store', 'Payments for {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.$this->title.'</h3>',
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
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => $template,
	            'buttons' => [
	                'account' => function ($url, $model) {
	                    return $model->account ?
								Html::a(Yii::t('store', 'Account'),
									Url::to(['/accnt/account/view', 'id' => $model->account_id]),
									[
			                        	'title' => Yii::t('store', 'Account'),
			                    	])
								:
								''
								;
	                },
	            ],
			],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
