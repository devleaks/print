<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="document-line-label">
	
	<table width="100%">
	<tr>
			<td style="text-align: center;"><?= $picture ? Html::img(Url::to($picture->getThumbnailUrl(), true)) : '<no pic>' ?></td>
			<td width="80%" style="font-size: 92; text-align: right;"><?= $model->document->name ?></td>
	</tr>
	<tr>
			<td style="font-size: 40; text-align: center;"><?= $sequence.' / '.$model->quantity ?></td>
			<td width="80%" style="font-size: 40; text-align: right;"><?= $model->document->client->nom ?></td>
	</tr>
	<?= $model->work_width != '' ? '<tr><td style="text-align:center;">'.$model->work_width.' cm &times; '.$model->work_height.' cm</td><td></td></tr>': '' ?>
	</table>

	<br>
	<br>

	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Order') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Due Date') ?></td>
	</tr>
	<tr>
			<td style="font-size: 40;"><?= Yii::$app->formatter->asDate($model->created_at, 'medium') ?></td>
			<td style="font-size: 40;"><?= Yii::$app->formatter->asDate($model->due_date, 'medium') ?></td>
	</tr>
	</table>

	<br>
	<br>

	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
		<td style="font-size: 40; text-align:left;"><?= $model->item->libelle_long ?></td>
		<td style="font-size: 40; text-align:center;"><?= $model->position.' / '.$model->document->getLineCount() ?></td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 20; text-align:left;"><?= $model->hasDetail() ? $model->getDetail()->getDescription(false) : '' ?></td>
	</tr>
	<?= $model->note != '' ? '<tr><td colspan="2" style="text-align:left;">Note: '.$model->note.'</td></tr>': '' ?>
	</table>

</div>