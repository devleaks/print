<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="order-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;"><barcode code="<?= $this->render('order_qrcode', ['model'=>$model]) ?>" size="1" type="QR" error="M" class="barcode" /></td>
			<td width="80%" style="font-size: 3em; text-align: right;"><?= $model->name ?></td>
	</tr>
	</table>
	<br>
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('print', 'Order') ?></td>
			<th style="text-align: center;"><?= Yii::t('print', 'Due Date') ?></td>
	</tr>
	<tr>
			<td style="font-size: 2em;"><?= Yii::$app->formatter->asDate($model->created_at, 'medium') ?></td>
			<td style="font-size: 2em;"><?= Yii::$app->formatter->asDate($model->due_date, 'medium') ?></td>
	</tr>
	<tr>
			<td colspan="2" style="font-size: 3em; text-align:left;"><?= $model->client->nom ?></td>
	</tr>
	<tr>
			<td colspan="2" style="font-size: 3em; text-align:left;">Total: <?= $model->getDocumentLines()->count(); ?></td>
	</tr>
	</table>

</div>