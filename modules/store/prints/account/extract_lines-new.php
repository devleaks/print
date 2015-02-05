<?php
use app\models\Account;
use app\models\Parameter;
use app\widgets\GridViewPDF;

?>
<div class="account-line-print">
<table width="100%" class="table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th style="text-align: center;"><?= Yii::t('print', 'Reference') ?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Order Date')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Due Date')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Amount')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Bill Date')?></th>
		<th style="text-align: center;"><?= Yii::t('print', 'Note')?></th>
	</tr>
	<tr>
		<th colspan="3" style="text-align: right;"><?=  Yii::t('print', 'Opening Balance') . ' ' . Yii::t('print', 'on') . ' ' . Yii::$app->formatter->asDate($to_date) ?></th>
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
							Parameter::getTextValue('payment', $model->payment_method, '').'. '.Yii::t('print', 'Thank You').'.'
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
<?= Yii::t('print', 'Closing Balance') . ' ' . Yii::t('print', 'on') . ' ' . Yii::$app->formatter->asDate(date('Y-m-d', strtotime('now'))) ?></th>
		<th style="text-align: right;"><?= Yii::$app->formatter->asCurrency($closing_balance) ?></th>
		<th colspan="2"></th>
	</tr>
	</tfoot>
</table>
</div>
<div>
	<?= GridViewPDF::widget([
		'dataProvider' => $dataProvider,
		'caption' => 'Hello',
		'showPageSummary' => true,
		'showBefore' => true,
		'showAfter' => true,
		'columns' => [
			[
				'label' => Yii::t('print', 'Reference'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ? $model->document->name : (
					  ($model->status == Account::TYPE_CREDIT && $model->amount > 0) ?
							Parameter::getTextValue('payment', $model->payment_method, '').'. '.Yii::t('print', 'Thank You').'.'
							: ''
					);
	            },
			],
			[
				'label' => Yii::t('print', 'Order Date'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ?  Yii::$app->formatter->asDate($model->document->created_at) : '';
	            },
			],
			[
				'label' => Yii::t('print', 'Due Date'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document ?  Yii::$app->formatter->asDate($model->document->due_date) : '';
	            },
			],
			[
				'attribute' => 'amount',
				'hAlign' => GridViewPDF::ALIGN_RIGHT,
				'pageSummary' => true,
				'format' => 'currency'
			],
			[
				'attribute' => 'created_at',
				'format' => 'date'
			],
			[
				'attribute' => 'note',
			]
		]
	]) ?>
</div>
