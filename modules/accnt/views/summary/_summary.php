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
$query->from('account');
if($searchModel->created_at != '') {
	$day_start = $searchModel->created_at. ' 00:00:00';
	$day_end   = $searchModel->created_at. ' 23:59:59';
	$query->andWhere(['>=','created_at',$day_start])
		  ->andWhere(['<=','created_at',$day_end]);
}

$q = new Query(); // dummy query in case no data found
$q->select([
	'payment_method' => 'concat("CASH")',
	'total_count' => 'sum(0)',
	'total_amount' => 'sum(0)',
]);

$dataProvider = new ActiveDataProvider([
	'query' => $query->select(['payment_method',
						'tot_count' => 'count(id)',
						'tot_amount' => 'sum(amount)'])
	                 ->where(['not', ['payment_method' => Payment::CASH]])
					 ->groupBy(['payment_method'])
					 ->union($q)
]);

if($searchModel->created_at != '') { //?
	$dataProvider->query
		->andWhere(['>=','created_at',$day_start])
		->andWhere(['<=','created_at',$day_end]);
}

?>

<div class="payment-summary">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'toolbar' => false,
		'panel' => [
	        'heading'=> '<h3 class="panel-title"><a name="TOP"></a>'.Yii::t('store', 'Payments').' – '.Yii::$app->formatter->asDate($searchModel->created_at, 'long').'</h3>',
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
	                    return Html::a(Parameter::getTextValue('payment', $model['payment_method']), '#'.$model['payment_method']);
	            },
				'format' => 'raw',
            ],
			[
				'attribute' => 'tot_amount',
				'label' => Yii::t('store', 'Total'),
				'format' => 'currency',
				'hAlign' => GridView::ALIGN_RIGHT,
				'noWrap' => true,
			    'pageSummary' => true,
	            'value' => function ($model, $key, $index, $widget) use ($cash_amount) {
	                    return $model['payment_method'] == Payment::CASH ? $cash_amount : $model['tot_amount'];
	            },
			],
			[
				'attribute' => 'tot_count',
				'label' => Yii::t('store', 'Quantity'),
				'hAlign' => GridView::ALIGN_CENTER,
			    'pageSummary' => true,
	            'value' => function ($model, $key, $index, $widget) use ($cash_count) {
	                    return $model['payment_method'] == Payment::CASH ? $cash_count : $model['tot_count'];
	            },
			],
        ],
	    'showPageSummary' => true,
    ]); ?>

</div>

</div>
