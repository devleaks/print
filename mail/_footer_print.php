<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */
?>
<div class="order-print-footer">
	<br>
	<table width="100%" class="table table-bordered" style="text-align: center;">
	<tr>
			<th style="text-align: center;"><?= Yii::t('store', 'Paid Today') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Advance') ?></td>
			<th style="text-align: center;"><?= Yii::t('store', 'Solde') ?></td>
	</tr>
	<tr>
			<td></td>
			<td></td>
			<td><?= Yii::$app->formatter->asCurrency($model->vat_bool ? $model->price_htva : $model->price_tvac) ?></td>
	</tr>
	</table>


	<hr>
	<div class="order-print" style="text-align: center; font-size: 9px;">
	Labo JJ Micheli SPRL • 21-23 rue de Tervaete • 1040 Bruxelles<br>
	Tél. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>
	e-Mail: info@labojjmicheli.be • Site Web: www.labojjmicheli.be • TVA: BE 428 746 631 RPM: BXL<br>
	Banque BNP Paribas Fortis 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB
	</div>
</div>
