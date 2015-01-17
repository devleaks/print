<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
$this->title = Yii::t('store', $model->document_type) . ' ' . $model->name;

$lang = ($model->client->lang ? $model->client->lang : 'fr');
Yii::$app->language = $lang;
?>
<div class="document-print-body">

	<?= $this->render('header', [
			'model' => $model,
	    ])
	?>

	<?= $this->render('table', [
	        'query' => $model->getDocumentLines(),
			'order' => $model
	    ])
	?>

	<?= $this->render('footer', [
			'model' => $model,
	    ])
	?>

</div>
