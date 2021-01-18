<?php
/*
 * Generic header for all printed documents
 *
 * @var $this yii\web\View 
 * @var $format string
 * @var $language string
 */

use yii\helpers\Html;
use app\models\PdfDocument;


if(!isset($format))
	$format = PdfDocument::FORMAT_A4;

$w = ($format == PdfDocument::FORMAT_A4) ? 200 : floor(200/sqrt(2)) ;
$h = ($format == PdfDocument::FORMAT_A4) ? 141 : floor( 64/sqrt(2)) ;

?>
<div class="print-header">	
	<?= Html::img('@app/assets/i/logo-paper.jpg', ['width' => $w, 'height' => $h]) ?>
</div>