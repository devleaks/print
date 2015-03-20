<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="bill-line-print">
<table width="100%" class="table table-bordered" style="font-size: 60%;">
	<thead>
	<tr>
		<th><?= Yii::t('print', 'Date') ?></th>
		<th><?= Yii::t('print', 'Amount') ?></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($model->getPayments()->each() as $payment): ?>
	<tr>
		<td><?= Yii::$app->formatter->asDate($payment->created_at) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($payment->amount) ?></td>
		<td><?= $payment->getPaymentMethod() ?></td>
		<?php $tot_amount += $payment->amount; ?>
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
