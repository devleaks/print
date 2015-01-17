<?php
use app\models\Account;
use app\models\Parameter;
?>
<div class="account-line-print">
<table width="100%" class="table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('store', 'Reference') ?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Order Date')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Due Date')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Amount')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Bill Date')?></th>
		<th style="text-align: center;"><?= Yii::t('store', 'Note')?></th>
	</tr>
	<tr>
		<th colspan="3" style="text-align: right;"><?=  Yii::t('store', 'Opening Balance') . ' ' . Yii::t('store', 'on') . ' ' . Yii::$app->formatter->asDate($to_date) ?></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($opening_balance) ?></th>
		<th colspan="2"></th>
	</tr>
	</thead>
	<tbody>
<?php
	$tot_amount = 0;
	foreach($lines->each() as $model): ?>
	<tr>
		<td><?= $model->document ? $model->document->name : (
					  ($model->status == Account::TYPE_CREDIT && $model->amount > 0) ?
							Parameter::getTextValue('paiement', $model->payment_method, '').'. '.Yii::t('store', 'Thank You').'.'
							: ''
					) ?></td>
		<td><?= $model->document ? Yii::$app->formatter->asDate($model->document->created_at) : '' ?></td>
		<td><?= $model->document ? Yii::$app->formatter->asDate($model->document->due_date) : '' ?></td>
		<td style="text-align: right;"><?= Yii::$app->formatter->asCurrency($model->amount) ?></td>
		<td><?= Yii::$app->formatter->asDate($model->created_at) ?></td>
		<td style="text-align: left;"><?= $model->note ?></td>
		<?php $tot_amount += $model->amount; ?>
	</tr>
<?php endforeach; ?>
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="3" style="text-align: right;">
<?= Yii::t('store', 'Closing Balance') . ' ' . Yii::t('store', 'on') . ' ' . Yii::$app->formatter->asDate(date('Y-m-d', strtotime('now'))) ?></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($closing_balance) ?></th>
		<th colspan="2"></th>
	</tr>
	</tfoot>
</table>
</div>
