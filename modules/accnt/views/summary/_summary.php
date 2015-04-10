<?php

use app\models\Parameter;
use app\models\Payment;
use app\models\PaymentSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\Query;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$query = new Query();
$query->from('payment');
if($searchModel->created_at != '') {
	$day_start = $searchModel->created_at. ' 00:00:00';
	$day_end   = $searchModel->created_at. ' 23:59:59';
	$query->andWhere(['>=','created_at',$day_start])
		  ->andWhere(['<=','created_at',$day_end]);
}

$dataProvider = new ActiveDataProvider([
	'query' => $query->select(['payment_method, count(id) as tot_count, sum(amount) as tot_amount'])
					 ->groupBy(['payment_method'])
]);
?>

<div class="payment-summary">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => [
			'{export}',
    	],
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.Yii::t('store', 'Payments').' – '.Yii::$app->formatter->asDate($searchModel->created_at, 'long').'</h3>',
	        'before'=> ' ',
	        'after'=> false,
			'footer' => false,
	    ],
		'panelHeadingTemplate' => '{heading}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

			[
				'label' => Yii::t('store', 'Payment Method'),
				'attribute' => 'payment_method',
	            'value' => function ($model, $key, $index, $widget) {
	                    return Parameter::getTextValue('payment', $model['payment_method']);
	            },
            ],
			[
				'attribute' => 'tot_amount',
				'label' => Yii::t('store', 'Total'),
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
			],
			[
				'attribute' => 'tot_count',
				'label' => Yii::t('store', 'Quantity'),
				'hAlign' => GridView::ALIGN_CENTER,
			    'pageSummary' => true,
			],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>

</div>
