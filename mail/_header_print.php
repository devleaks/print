<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */
?>
<div class="order-print-header">
	
	<table width="20%" id="logo" style="text-align: center;">
	<tr><td>labo</td></tr>
	<tr><td style="font-size: 16px;">JJ. MICHELI</td></tr>
	<tr style="background-color: black;"><td style="color: white; font-size: 10px;">d i g i t  a l&nbsp;&nbsp;&nbsp;i m a g i n g</td></tr>
	</table>
	<br>
	<br>
	<table width="100%">
	<tr>
			<td style="text-align: center;"><barcode code="<?= $model->getClient()->one()->getAddress() ?>" size="1" type="QR" error="M" class="barcode" /></td>
			<td width="50%"><?= $model->getClient()->one()->getAddress() ?></td>
	</tr>
	</table>
	<br>
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Date') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Reference Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Tva Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Reference Operation') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Numero') ?></td>
	</tr>
	<tr>
			<td><?= Yii::$app->formatter->asDate(date('d/m/y'), 'd/m/y') ?></td>
			<td><?= $model->reference_client ?></td>
			<td><?= $model->client->numero_tva ?></td>
			<td><?= $model->reference ?></td>
			<td><?= $model->name ?></td>
	</tr>
	</table>
	<br>

</div>