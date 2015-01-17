<?php
/*
 * Generic cover letter (A4 format)
 *
 * @var $this yii\web\View 
 * @var $model app\models\CoverLetter
 */

// change app language to generate view in user's language. Defaults to app language.
if(in_array($model->client->lang, ['fr', 'nl', 'en'])) Yii::$app->language = $model->client->lang;

?>
<table width="100%">
	<tr>
		<td style="text-align: center;"></td>
		<td width="40%" style='font-size: 14px;'><?= $this->render('client', ['model' => $model->client]) ?></td>
	</tr>
</table>
<br>
<br>
<br>
<br>
<p style="text-align: right;">
	<?= Yii::t('store', 'Brussels, {0}', $model->date) ?>
</p>
<br>
<br>
<p>
	<strong><?= Yii::t('store', 'Object') ?></strong>: <?= $model->subject ?>
</p>
<p>
	<?= $model->client->titre.' '.$model->client->nom ?>,
</p>
<br>
<br>
<br>
<p>
	<?= Yii::t('store', $model->type.'::BEFORE_LIST') ?>
</p>
<br>

<?= $model->table ?> // ::LIST !

<p>
	<?= Yii::t('store', $model->type.'::AFTER_LIST') ?>
</p>
<p>
	<?= Yii::t('store', $model->type.'::CALL_ACTION') ?>
</p>
<p>
	<?= Yii::t('store', $model->type.'::GREETINGS') ?>
</p>
