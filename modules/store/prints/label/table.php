<?php

?>
<div class="bill-line-print">
<table width="100%" class="table table-bordered">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('print', 'Ref.') ?></th>
		<th style="text-align: left;"><?= Yii::t('print', 'Item')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Qty')?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($dataProvider->query->each() as $model): ?>
	<tr>
		<td><?= $model->item->reference ?></td>
		<td><?= $model->getDescription() ?></td>
		<td style="text-align: center;"><?= $model->quantity ?></td>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
</table>
</div>
