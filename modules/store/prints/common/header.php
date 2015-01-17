<?php
/*
 * Generic header for all printed documents
 *
 * @var $this yii\web\View 
 * @var $format string
 * @var $language string
 */

use yii\helpers\Html;
use app\components\PdfDocumentGenerator;


if(!isset($format))
	$format = PdfDocumentGenerator::FORMAT_A4;

$w = ($format == PdfDocumentGenerator::FORMAT_A4) : 200 : floor(200/sqrt(2)) ;
$h = ($format == PdfDocumentGenerator::FORMAT_A4) :  64 : floor( 64/sqrt(2)) ;

?>
<div class="print-header">	
	<?= Html::img('@app/assets/i/logo-bw.png', ['width' => <?= $w ?>, 'height' => <?= $h ?>]) ?>
</div>