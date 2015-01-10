<?php
use app\models\Document;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="order-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;"></td>
			<td width="40%" style='font-size: 14px;'><?= $this->render('_print_client', ['model' => $model->client]) ?></td>
	</tr>
	</table>
	<br>
	<br>
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
			<td><?= Yii::$app->formatter->asDate($model->created_at, 'short') ?></td>
			<td><?= $model->reference_client ?></td>
			<td><?= $model->client->numero_tva ?></td>
			<td><?= '+++ '.$model->reference.' +++' ?></td>
			<td><?= $model->name ?></td>
	</tr>
	<?php if($model->note != ''): ?>
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Note') ?></th>
			<td colspan="4" style="text-align: left;"><?= $model->note ?></td>
	</tr>
	<?php endif; ?>
	</table>
	<br>
	
	<h4><?= Yii::t('store', ($model->document_type == Document::TYPE_ORDER && $model->bom_bool) ? Document::TYPE_BOM : $model->document_type).' '.$model->name ?></h4>

</div>