<?php
/*
 * Generic cover letter (A4 format)
 *
 * @var $this yii\web\View 
 * @var $model app\models\CoverLetter
 */

// change app language to generate view in user's language. Defaults to app language.
if(in_array($model->client->lang, ['fr', 'nl', 'en'])) Yii::$app->language = $model->client->lang;
// set forceTranslation for English on this page only
Yii::$app->i18n->translations['store']->forceTranslation = true
?>
<table width="100%">
	<tr>
		<td style="text-align: center;"></td>
		<td width="40%" style='font-size: 14px;'><?= $model->client ? $this->render('client', ['model' => $model->client]) : '' ?></td>
	</tr>
</table>
<br>
<br>
<br>
<br>
<p style="text-align: right;">
	<?= Yii::t('print', 'Wavre, {0}', $model->date) ?>
</p>
<br>
<br>
<p>
	<strong><?= Yii::t('print', 'Object') ?></strong>: <?= $model->subject ?>
</p>
<p>
	<?php
		if($model->client->titre && $model->client->nom)
	 		echo $model->client->titre.' '.$model->client->nom.',';
	?>
</p>
<br>
<br>
<br>
<p>
	<?= Yii::t('print', $model->type.'::BEFORE_LIST') ?>
</p>
<br>

<?= $model->table ?>

<p>
	<?= Yii::t('print', $model->type.'::AFTER_LIST') ?>
</p>
<p>
	<?= Yii::t('print', $model->type.'::CALL_ACTION') ?>
</p>
<p>
	<?= Yii::t('print', $model->type.'::GREETINGS') ?>
</p>
