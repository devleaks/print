<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
use yii\data\ActiveDataProvider;

$client_nom = $model->client->nom;
if($model->client->auComptoir()) {
	if($model->reference_client)
		$client_nom = $model->reference_client;
}

?>
<div class="order-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;"><barcode code="<?= $this->render('qrcode_url', ['model'=>$model]) ?>" size="1" type="QR" error="M" class="barcode" /></td>
			<td width="80%" style="font-size: 5em; text-align: right;"><?= $model->name ?></td>
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
			<td colspan="2" style="font-size: <?= ($model->client->auComptoir() ? 1.5 : 3) ?>em; text-align:left;"><?= $client_nom ?></td>
	</tr>
	<tr>
			<td colspan="2" style="font-size: 2em; text-align:left;">Total: <?= $model->getDocumentLines()->count(); ?></td>
	</tr>
	<?php if ($model->note): ?>
	<tr>
			<td colspan="2" style="font-size: 1em; text-align:left;">Note: <?= $model->note; ?></td>
	</tr>
	<?php endif; ?>
	</table>

</div>
	<?= $this->render('table', [ // 'table-gv'
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getDocumentLines(),
					'pagination' => false,
			]),
			'order' => $model
	    ])
	?>

	<?= $this->render('pics', [ // 'table-gv'
			'order' => $model
	    ])
	?>

