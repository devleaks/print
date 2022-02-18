<?php
use app\models\Parameter;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="document-print-footer">
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;page-break-inside:avoid;">
	<tr>
		<th width="15%" style="text-align: center;"><?= Yii::t('print', 'Due Date') ?></th>
		<td width="15%" style="text-align: center;"><?= Yii::$app->formatter->asDate($model->due_date) ?></td>

		<th width="13%" style="text-align: center;"><?= Yii::t('print', 'Taxable') ?></th>
		<th width="13%" style="text-align: center;"><?= Yii::t('print', 'VAT') ?></th>
		<th width="13%" style="text-align: center;"><?= Yii::t('print', 'VAT Rate') ?></th>

		<th width="15%" style="text-align: center;"><?= Yii::t('print', 'VAT excl.') ?></th>
		<td width="15%" style="text-align: ',' center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>

		<td style="text-align: center;"><?= Yii::$app->formatter->asCurrency($model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
		<td style="text-align: center;"><?= $model->vat_bool ? '' : ($model->vat ? $model->vat: '21'.'&nbsp;%') ?></td>

		<th style="text-align: center;"><?= Yii::t('print', 'VAT') ?></th>
		<td style="text-align: ',' center;"><?= $model->vat_bool ? '' : Yii::$app->formatter->asCurrency($model->price_tvac - $model->price_htva) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="3"></td>

		<td style="text-align: center; font-weight: bold; font-size: 1.8em;"><?= Yii::t('print', 'VAT incl.') ?></td>
		<td style="text-align: ',' center; font-weight: bold; font-size: 1.8em;"><?= Yii::$app->formatter->asCurrency($model->vat_bool ? $model->price_htva : $model->price_tvac) ?></td>
	</tr>
	</table>

	<br>
	<?php if($model->vat_bool && $model->legal): ?>
		<p><?= Parameter::getMLText('legal', $model->legal) ?></p>
		<br>
	<?php endif; ?>

	<table width="100%" class="table table-bordered no-break" style="text-align: center;page-break-inside:avoid;">
	<tr>
			<th style="text-align: center;" width="33%"><?= Yii::t('print', 'Paid Today') ?></th>
			<th style="text-align: center;" width="33%"><?= Yii::t('print', 'Advances') ?></th>
			<th style="text-align: center;" width="33%"><?= Yii::t('print', 'Solde') ?></th>
	</tr>
	<tr>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid(true)) ?></td>
			<td><?= Yii::$app->formatter->asCurrency($model->getPrepaid()) ?></td>
			<td><?= Yii::$app->formatter->asCurrency( ($model->vat_bool ? $model->price_htva : $model->price_tvac) - $model->getPrepaid()) ?></td>
	</tr>
	<?php if(false && ($model->getPayments()->count()>0)) {
			echo '<tr><td></td><td>';
			echo $this->render('_payments', [
				'model' => $model,
			]);
			echo '</td><td></td></tr>';
		}
	?>
	</table>
	
</div>
