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

$q = new Query();
$q->select([
	'payment_method' => 'concat("CASH")',
	'total_count' => 'sum(0)',
	'total_amount' => 'sum(0)',
]);

$dataProvider = new ActiveDataProvider([
	'query' => $query->select(['payment_method, count(id) as tot_count, sum(amount) as tot_amount'])
	                 ->where(['not', ['payment_method' => Payment::CASH]])
					 ->groupBy(['payment_method'])
					 ->union($q)
]);
?>

<div class="daily-summary-summary">
	
	<h2><?= Yii::t('store', 'Daily Summary for {0}', Yii::$app->formatter->asDate($searchModel->created_at)) ?></h2>
	
<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Payment Method') ?></th>
		<th style="text-align: left;"><?= Yii::t('store', 'Total') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Quantity') ?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($dataProvider->query->each() as $model): ?>
	<tr>
		<td><?= $model['payment_method'] ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model['payment_method'] == Payment::CASH ? $cash_amount : $model['tot_amount']) ?></td>
		<td style="text-align: center;"><?= $model['payment_method'] == Payment::CASH ? $cash_count : $model['tot_count'] ?></td>
		<?php $tot_amount += $model['payment_method'] == Payment::CASH ? $cash_amount : $model['tot_amount']; ?>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
</div>
