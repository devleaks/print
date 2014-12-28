<?php
use yii\data\ActiveDataProvider;

Yii::$app->language = ($model->lang ? $model->lang : 'fr');
?>
<p></p>
<div class="account-print-header">
	<table width="100%">
	<tr>
			<td style="text-align: center;">&nbsp;</td>
			<td width="40%" style='font-size: 14px;'>
					<span style='font-weight: bold;'><?= $model->prenom.' '.$model->nom ?></span><br>
					<?= $model->autre_nom ?><br>
					<?= $model->adresse ?><br>
					<?= $model->code_postal.' '.$model->localite ?><br>
					<?= ($model->pays != '' && !in_array(strtolower($model->pays), ['belgique','belgie','belgium'])) ? '<br>'.$model->pays.'<br>' : '' ?>
			</td>
	</tr>
	</table>
	<br>
	<br>
	<br>
	<br>
	<p></p>
	<table width="100%">
	<tr>
			<td style="text-align: center;">&nbsp;</td>
			</td>
	</tr>
	</table>
</div><div class="account-print">
	<?= $this->render('_print_account', ['dataProvider' => new ActiveDataProvider(['query'=>$model->getAccounts(), 'sort' => false]), 'client' => $model])  ?>
</div>
