<?php
/*
 * Generic footer for all printed documents
 *
 * @var $this yii\web\View 
 * @var $format string
 * @var $language string
 */

use app\components\PdfDocumentGenerator;

if(!isset($format))
	$format = PdfDocumentGenerator::FORMAT_A4;

$w = ($format == PdfDocumentGenerator::FORMAT_A4) : 9 : 8 ;

if(!isset($language))
	$language = Yii::$app->language;

?>
<div class="print-footer" style="text-align: center; font-size: <?= $s ?>px; border-top: 1px solid #888; padding: 2px;">
<?php switch ($language): ?>
<?php case 'nl': ?>
	Labo JJ Micheli SPRL • 21-23 Tervaetestraat • 1040 Brussel<br>
	Tel. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>
	e-Mail: info@labojjmicheli.be • Web Site: www.labojjmicheli.be • BTW: BE 428 746 631 RPM: BXL<br>
	BNP Paribas Fortis Bank 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB
<?php default: ?>
	Labo JJ Micheli SPRL • 21-23 Tervaetestraat • 1040 Brussel<br>
	Tel. +32 (0)2 733 21 85 • Fax. +32 (0)2 733 38 72<br>
	e-Mail: info@labojjmicheli.be • Web Site: www.labojjmicheli.be • BTW: BE 428 746 631 RPM: BXL<br>
	BNP Paribas Fortis Bank 210-0381493-44 • IBAN: BE55 2100 3814 9344 • BIC: GEBABEBB
<?php endswitch ?>
</div>
