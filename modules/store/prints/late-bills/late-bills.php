<?php

?>
<div class="late-bills">
<table class="table">
	<thead>
	<tr>
		<th><?= Yii::t('store', 'Bill') ?></th>
		<th><?= Yii::t('store', 'Order Due Date')?></th>
		<th><?= Yii::t('store', 'Bill Issue Date')?></th>
		<th><?= Yii::t('store', 'Bill Due Date')?></th>
		<th><?= Yii::t('store', 'Amount')?></th>
		<th><?= Yii::t('store', 'Prepaid')?></th>
		<th><?= Yii::t('store', 'Due')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($bills as $model): ?>
	<tr>
		<td><?= $model->name ?></td>
		<td><?= Yii::$app->formatter->asDate($model->due_date) ?></td>
		<td><?= Yii::$app->formatter->asDate($model->created_at) ?></td>
		<td><?= Yii::$app->formatter->asDate(date('Y-m-d', strtotime("+ 1 month", strtotime($model->created_at)))) ?></td>
		<td><?= Yii::$app->formatter->asCurrency($model->price_tvac) ?></td>
		<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid()) ?></td>
		<td><?= Yii::$app->formatter->asCurrency($model->price_tvac - $model->getPrepaid()) ?></td>
		<?php $tot_amount += ($model->price_tvac - $model->getPrepaid()); ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="6" style="text-align: right;"><?= Yii::t('store', 'Total') ?></th>
		<th><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
	</tr>
	</tfoot>
</table>
</div>