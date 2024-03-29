<?php

use app\models\Account;
use app\models\Document;
use app\models\Payment;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dataProvider->sort->attributes  = null;
?>
<div class="payment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title"><a name="'.$method.'"></a>'.Yii::t('store', 'Payments').': '.$label.'</h3>',
	        'before'=> false,
	        'after'=> false, // Html::submitButton(Yii::t('store', 'Partial BOM'), ['class' => 'btn btn-primary']),
			'footer' => false,
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
	        [
				'attribute' => 'order_name',
	            'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
						if($client = $model->client)
                    		return $client->nom;
						else
							return '';
	            },
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'order',
	            'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
							if($account = Account::findOne($model->id)) {
								return $account->whatFor();
							} else
                    			return '';
	            },
	        ],
			[
				'attribute' => 'payment_date',
            	'label' => Yii::t('store', 'Payment Date'),
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return $model->payment_date;
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
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            //['class' => 'kartik\grid\ActionColumn'],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>
