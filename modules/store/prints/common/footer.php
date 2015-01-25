<?php
/*
 * Generic footer for all printed documents
 *
 * @var $this yii\web\View 
 * @var $format string
 * @var $language string
 */

use app\models\PdfDocument;

if(!isset($format))
	$format = PdfDocument::FORMAT_A4;

$fontSize = ($format == PdfDocument::FORMAT_A4) ? 9 : 8 ;

if(!isset($language))
	$language = Yii::$app->language;

?>
<div class="print-footer" style="text-align: center; font-size: <?= $fontSize ?>px; border-top: 1px solid #888; padding: 2px;">
<?= Yii::t('print', 'Labo JJ Micheli SPRL • 21-23 rue de Tervaete • 1040 Brussels<br>Tél. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>e-Mail: info@labojjmicheli.be • Web Site: www.labojjmicheli.be • VAT: BE 428 746 631 RPM: BXL<br>BNP Paribas Fortis Bank 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB') ?>
</div>
