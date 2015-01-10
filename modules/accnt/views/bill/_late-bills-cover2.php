<?php

$lang = ($client->lang ? $client->lang : 'fr');
Yii::$app->language = $lang;

?>
	<table width="100%">
	<tr>
			<td style="text-align: center;"></td>
			<td width="40%" style='font-size: 14px;'><?= $this->render('_print_client', ['model' => $client]) ?></td>
	</tr>
	</table>
	<br>
	<br>
	<br>
	<br>
<p style="text-align: right;">
<?= Yii::t('store', 'Brussels, {0}', $date) ?>
</p>
	<br>
	<br>
<p>
<strong><?= Yii::t('store', 'Object') ?></strong>: <?= $subject ?>
</p>
<?= $client->titre.' '.$client->nom ?>,
	<br>
	<br>
	<br>
<p>
<?= Yii::t('store', 'LATE_BILL_COVER2::BEFORE_LIST') ?>
</p>

<?= $this->render('_late-bills', ['bills' => $bills]) ?>

<p>
<?= Yii::t('store', 'LATE_BILL_COVER2::AFTER_LIST') ?>
</p>
<p>
<?= Yii::t('store', 'LATE_BILL_COVER2::CALL_ACTION') ?>
</p>
<p>
<?= Yii::t('store', 'LATE_BILL_COVER2::GREETINGS') ?>
</p>
