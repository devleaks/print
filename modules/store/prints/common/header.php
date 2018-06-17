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

?>
<div class="print-header">	
	<?= Html::img('@app/assets/i/cl.png', ['width' => 1300]) ?>
</div>