<?php
use app\models\Order;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* TOTALS */
$q = Order::find()
	->select(['client_id', 'year' => 'year(created_at)', 'tot_price' => 'sum(price_htva)', 'tot_count' => 'count(id)'])
	->andWhere(['client_id' => $model['client_id']])
	->groupBy('year')
	->orderBy('year')
	->asArray()->all();

$dp = new ArrayDataProvider([
	'allModels' => $q
]);

/* YEAR TO DATE */
$timeClause = '';
for($year = 2015; $year <= date('Y'); $year++) {
	if($timeClause != '')
		$timeClause .= ' or ';
	$timeClause .= "(created_at between '".$year."/01/01' and '".$year.date('/m/d')."')";
}

$q2 = Order::find()
	->select(['client_id', 'year' => 'year(created_at)', 'tot_price' => 'sum(price_htva)', 'tot_count' => 'count(id)'])
	->andWhere(['client_id' => $model['client_id']])
	->andWhere($timeClause)
	->groupBy('year')
	->orderBy('year')
	->asArray()->all();

$dp2 = new ArrayDataProvider([
	'allModels' => $q2
]);


?>
<div class="client-detail">
	
	<h3>Année Entière</h3>

    <?= GridView::widget([
        'dataProvider' => $dp,
        'columns' => [
			[
				'attribute' => 'year',
				'label' => Yii::t('store','Year'),
			],
			[
				'attribute' => 'test',
				'label' => Yii::t('store','Par mois'),
				'format' => 'raw',
			    'value' => function ($model, $key, $index, $widget) {
					//return print_r(Order::getSparkline($model['client_id'], $model['year'].'/01/01', $model['year'].'/12/31'), true);
					return \machour\sparkline\Sparkline::widget([
					    'clientOptions' => [
					        'type' => 'bar', 
					        'height' => 20, 
					        'barColor' => '#00a65a',
					    ],
					    'data' => Order::getSparkline($model['client_id'], $model['year'].'/01/01', $model['year'].'/12/31')
					]);
				}
			],
			[
				'attribute' => 'tot_price',
				'format' => 'currency',
				'label' => Yii::t('store','Montant commandes'),
				'hAlign' => GridView::ALIGN_RIGHT,
			],
			[
				'attribute' => 'tot_count',
				'format' => 'integer',
				'label' => Yii::t('store','Nb. commandes'),
				'hAlign' => GridView::ALIGN_CENTER,
			],
        ],
    ]) ?>

	<h3>Année Partielle (1er janvier - <?= strftime('%e %B')?>)</h3>

    <?= GridView::widget([
        'dataProvider' => $dp2,
        'columns' => [
			[
				'attribute' => 'year',
				'label' => Yii::t('store','Year'),
			],
			[
				'attribute' => 'tot_price',
				'format' => 'currency',
				'label' => Yii::t('store','Montant commandes'),
				'hAlign' => GridView::ALIGN_RIGHT,
			],
			[
				'attribute' => 'tot_count',
				'format' => 'integer',
				'label' => Yii::t('store','Nb. commandes'),
				'hAlign' => GridView::ALIGN_CENTER,
			],
        ],
    ]) ?>

</div>
