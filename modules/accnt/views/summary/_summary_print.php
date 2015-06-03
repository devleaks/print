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
?>
<div class="daily-summary-summary">
	
	<h3><?= Yii::t('store', 'Daily Summary for {0}', Yii::$app->formatter->asDate($searchModel->created_at)) ?></h3>
	
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
