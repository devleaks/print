<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
$this->title = Yii::t('store', $model->document_type) . ' ' . $model->name;

$lang = ($model->client->lang ? $model->client->lang : 'fr');
Yii::$app->language = $lang;
?>
<div class="order-print">

	<?= $this->render('_header_print', [
			'model' => $model,
	    ])
	?>

	<?= $this->render('_print_lines', [
	        'query' => $model->getDocumentLines(),
			'order' => $model
	    ])
	?>

	<?= $this->render('_footer_print', [
			'model' => $model,
	    ])
	?>

</div>
