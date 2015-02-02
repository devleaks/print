<?php

?>
<div class="frame-orders">
<table class="table">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Our Reference') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Due Date')?></th>
		<th><?= Yii::t('store', 'Item')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Width')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Height')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Quantity')?></th>
		<th><?= Yii::t('store', 'Note')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($frames as $model): ?>
	<tr>
		<td style="text-align: center;"><?= $model->reference ?></td>
		<td style="text-align: center;"><?= Yii::$app->formatter->asDate($model->due_date) ?></td>
		<td><?= $model->item ?></td>
		<td style="text-align: center;"><?= $model->width ?></td>
		<td style="text-align: center;"><?= $model->height ?></td>
		<td style="text-align: center;"><?= $model->quantity ?></td>
		<td><?= $model->note ?></td>
		<?php $tot_amount += $model->quantity; ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="5" style="text-align: right;"><?= Yii::t('store', 'Total') ?></th>
		<th style="text-align: center;"><?= $tot_amount ?></th>
		<th>&nbsp;</th>
	</tr>
	</tfoot>
</table>
</div>