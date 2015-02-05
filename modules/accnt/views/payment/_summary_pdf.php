<?php
use app\models\Parameter;
?>
<div class="payment-summary">
<table class="table">
	<thead>
	<tr>
		<th><?= Yii::t('store', 'Payment Method')?></th>
		<th><?= Yii::t('store', 'Total')?></th>
		<th><?= Yii::t('store', 'Quantity')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0; $tot_count = 0;
	foreach($totals->each() as $model): ?>
	<tr>
		<td><?= Parameter::getTextValue('payment', $model['payment_method']) ?></td>
		<td><?= Yii::$app->formatter->asCurrency($model['tot_amount']) ?></td>
		<td><?= $model['tot_count'] ?></td>
		<?php $tot_amount += $model['tot_amount']; $tot_count += $model['tot_count']; ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th></th>
		<th><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th><?= $tot_count  ?></th>
	</tr>
	</tfoot>
</table>
</div>
