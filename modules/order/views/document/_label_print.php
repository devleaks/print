<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="order-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;"><barcode code="<?= $this->render('_qrcode_content', ['model'=>$model]) ?>" size="1" type="QR" error="M" class="barcode" /></td>
			<td width="50%"><?= $model->getClient()->one()->getAddress() ?></td>
	</tr>
	</table>
	<br>
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Date') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Reference Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'VAT Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Reference Operation') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Numero') ?></td>
	</tr>
	<tr>
			<td><?= Yii::$app->formatter->asDate($model->due_date, 'short') ?></td>
			<td><?= $model->reference_client ?></td>
			<td><?= $model->client->numero_tva ?></td>
			<td><?= $model->reference ?></td>
			<td><?= $model->name ?></td>
	</tr>
	</table>

</div>