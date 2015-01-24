<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="document-print-footer">
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
		<th width="15%" style="text-align: center;"><?= Yii::t('store', 'Due Date') ?></th>
		<td width="15%" style="text-align: center;"><?= Yii::$app->formatter->asDate($model->due_date) ?></td>

		<th width="13%" style="text-align: center;"><?= Yii::t('store', 'Taxable') ?></th>
		<th width="13%" style="text-align: center;"><?= Yii::t('store', 'VAT') ?></th>
		<th width="13%" style="text-align: center;"><?= Yii::t('store', 'VAT Rate') ?></th>

		<th width="15%" style="text-align: center;"><?= Yii::t('store', 'HTVA') ?></th>
		<td width="15%" style="text-align: ',' center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>

		<td style="text-align: center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : $model->vat ? $model->vat: '21'.'&nbsp;%' ?></td>

		<th style="text-align: center;"><?= Yii::t('store', 'VAT') ?></th>
		<td style="text-align: ',' center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="3"></td>

		<td style="text-align: center; font-weight: bold;"><?= Yii::t('store', 'TVAC') ?></td>
		<td style="text-align: ',' center; font-weight: bold; font-size: 1.2em;"><?= Yii::$app->formatter->asCurrency($model->vat_bool ? $model->price_htva : $model->price_tvac) ?></td>
	</tr>
	</table>

	<br>

	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Paid Today') ?></th>
			<th style="text-align: center;"><?= Yii::t('store', 'Advances') ?></th>
			<th style="text-align: center;"><?= Yii::t('store', 'Solde') ?></th>
	</tr>
	<tr>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid(true)) ?></td>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid()) ?></td>
			<td><?= Yii::$app->formatter->asCurrency( ($model->vat_bool ? $model->price_htva : $model->price_tvac) - $model->getPrepaid()) ?></td>
	</tr>
	</table>
</div>
