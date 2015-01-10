<?php
use app\models\Parameter;
?>
<div class="payment-summary">
<table class="table">
	<caption><?= $method ?></caption>
	<thead>
	<tr>
		<th><?= Yii::t('store', 'Order')?></th>
		<th><?= Yii::t('store', 'Created At')?></th>
		<th><?= Yii::t('store', 'Amount')?></th>
		<th><?= Yii::t('store', 'Status')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($query->each() as $model): ?>
	<tr>
		<td><?= $model->getDocument()->one()->name ?></td>
		<td><?= Yii::$app->formatter->asDate($model->created_at) ?></td>
		<td><?= Yii::$app->formatter->asCurrency($model->amount) ?></td>
		<td><?= $model->status ?></td>
		<?php $tot_amount += $model->amount; ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th></th>
		<th></th>
		<th><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
</div>
