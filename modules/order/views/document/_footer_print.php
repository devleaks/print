<?php
use app\models\Parameter;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="order-print-footer">
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
		<td width="15%" style="text-align: center;"><?= Yii::t('store', 'Due Date') ?></td>
		<td width="15%" style="text-align: center;"><?= Yii::$app->formatter->asDate($model->due_date) ?></td>

		<td width="13%" style="text-align: center;"><?= Yii::t('store', 'Taxable') ?></td>
		<td width="13%" style="text-align: center;"><?= Yii::t('store', 'VAT') ?></td>
		<td width="13%" style="text-align: center;"><?= Yii::t('store', 'VAT Rate') ?></td>

		<td width="15%" style="text-align: center;"><?= Yii::t('store', 'HTVA') ?></td>
		<td width="15%" style="text-align: center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>

		<td style="text-align: center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : $model->vat ? $model->vat: '21'.'&nbsp;%' ?></td>

		<td style="text-align: center;"><?= Yii::t('store', 'VAT') ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="3"></td>

		<td style="text-align: center; font-weight: bold; font-size: 16px;"><?= Yii::t('store', 'TVAC') ?></td>
		<td style="text-align: center; font-weight: bold; font-size: 16px;"><?= Yii::$app->formatter->asCurrency($model->vat_bool ? $model->price_htva : $model->price_tvac) ?></td>
	</tr>
	</table>

	<?php
		if(/*$model->document_type == Document::TYPE_BILL &&*/ $model->legal) {
			echo Parameter::getTextValue('legal', $model->legal);
		}
		echo '<br/>';
	?>
	<br>

	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Paid Today') ?></th>
			<th style="text-align: center;"><?= Yii::t('store', 'Advance') ?></th>
			<th style="text-align: center;"><?= Yii::t('store', 'Solde') ?></th>
	</tr>
	<tr>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid(true)) ?></td>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid()) ?></td>
			<td><?= Yii::$app->formatter->asCurrency( ($model->vat_bool ? $model->price_htva : $model->price_tvac) - $model->getPrepaid()) ?></td>
	</tr>
	</table>
</div>
