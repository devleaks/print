<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<hr>
<div class="order-print-page-footer" style="text-align: center; font-size: 9px;">
<?php if($model->client->lang == 'nl'): ?>
	Labo JJ Micheli SPRL • 21-23 Tervaetestraat • 1040 Brussel<br>
	Tel. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>
	e-Mail: info@labojjmicheli.be • Web Site: www.labojjmicheli.be • BTW: BE 428 746 631 RPM: BXL<br>
	BNP Paribas Fortis Bank 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB
<?php else: ?>
	Labo JJ Micheli SPRL • 21-23 rue de Tervaete • 1040 Bruxelles<br>
	Tél. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>
	e-Mail: info@labojjmicheli.be • Site Web: www.labojjmicheli.be • TVA: BE 428 746 631 RPM: BXL<br>
	Banque BNP Paribas Fortis 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB
<?php endif; ?>
</div>
