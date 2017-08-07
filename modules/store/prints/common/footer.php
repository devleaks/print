<?php
/*
 * Generic footer for all printed documents
 *
 * @var $this yii\web\View 
 * @var $format string
 * @var $language string
 */

use yii\helpers\Html;
use app\models\PdfDocument;

if(!isset($format))
	$format = PdfDocument::FORMAT_A4;

$fontSize = ($format == PdfDocument::FORMAT_A4) ? 9 : 8 ;

if(!isset($language))
	$language = Yii::$app->language;

$title = !isset($title) ? '' : $title.' — ';
	
?>
<div style="text-align: center; font-size: <?= $fontSize ?>px; padding-bottom: 6px;"><?= $title ?>Page {PAGENO}/{nb}</div>
<div class="print-footer" style="text-align: center; border-top: 1px solid #888; padding: 2px;">
	<?= Html::img('@app/assets/i/footer-fr.png', ['width' => '80%']) ?>
</div>
