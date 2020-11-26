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

$lang = isset(Yii::$app->language) ? Yii::$app->language : (isset($language) ? $language : 'fr');

if(!isset($lang)||$lang=='')//we must have a value
	$lang='fr';
	

$title = !isset($title) ? '' : $title.' — ';
	
?>
<div style="text-align: center; font-size: <?= $fontSize ?>px; padding-bottom: 6px;">
<p><?= Yii::t('print', $title).' '.Yii::t('print', 'Page').' ' ?>{PAGENO}/{nb}</p>
<p style="font-size: <?= $fontSize - 1 ?>px;"><?= Yii::t('print', 'General Sales Conditions on our Website at www.joz-srl.be/contact/conditions-dutilisation') ?></p>
</div>
<div class="print-footer" style="text-align: center; border-top: 1px solid #888; padding: 2px;">
	<?= Html::img('@app/assets/i/footer-'.$lang.'.png', ['width' => '80%']) ?>
</div>
