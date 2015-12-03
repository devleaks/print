<?php
use app\models\Document;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="document-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;page-break-inside:avoid;"></td>
			<td width="40%" style='font-size: 1.1em;'><?= $this->render('../common/client', ['model' => $model->client]) ?></td>
	</tr>
	</table>
	<br>
	<br>
	<br>
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;page-break-inside:avoid;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('print', 'Date') ?></td>
			<th style="text-align: center;"><?= Yii::t('print', 'Reference Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('print', 'VAT Client') ?></td>
			<th style="text-align: center;"><?= Yii::t('print', 'Reference Operation') ?></td>
			<th style="text-align: center;"><?= Yii::t('print', 'Order') ?></td>
	</tr>
	<tr>
			<?php // see http://www.yiiframework.com/forum/index.php/topic/62050-the-message-file-for-category-yii-does-not-exist/
			if(Yii::$app->language == 'en')	Yii::$app->language = 'en-US';
			?>
			<td><?= Yii::$app->formatter->asDate($model->created_at, 'short') ?></td>
			<td><?= $model->reference_client ?></td>
			<td><?= $model->client->numero_tva ?></td>
			<td><?= '+++ '.$model->reference.' +++' ?></td>
			<td><?= $model->getRelatedReference() ?></td>
	</tr>
	<?php if($model->note != ''): ?>
	<tr>
			<th style="text-align: center;"><?= Yii::t('print', 'Note') ?></th>
			<td colspan="4" style="text-align: left;"><?= $model->note ?></td>
	</tr>
	<?php endif; ?>
	</table>
	<br>
	
	<h4><?= Yii::t('print', ($model->document_type == Document::TYPE_ORDER && $model->bom_bool) ? Document::TYPE_BOM : $model->document_type).' '.$model->name ?></h4>

</div>