<?php

?>
<div class="bill-line-print">
<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Item') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Label')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Quantity')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Unit Price')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Extra')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Price Htva')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Vat')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($query->each() as $model): ?>
	<tr>
		<td><?= $model->item->reference ?></td>
		<td><?= $model->getDescription() ?></td>
		<td style="text-align: center;"><?= $model->quantity ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->unit_price) ?></td>
		<td><?= $model->getExtraDescription(false) ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency(round($model->price_htva + $model->extra_htva, 2)) ?></td>
		<td style="text-align: center;"><?= $model->vat.'&nbsp;%' ?></td>
		<?php $tot_amount += round($model->price_htva + $model->extra_htva, 2); ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="5"></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($tot_amount) ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
</div>
