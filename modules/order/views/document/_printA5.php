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

	<?= $this->render('_header_printA5', [
			'model' => $model,
	    ])
	?>

	<?= $this->render('../document-line/_printA5', [
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getDocumentLines(),
					'pagination' => false,
			]),
			'order' => $model
	    ])
	?>

	<?= $this->render('_footer_printA5', [
			'model' => $model,
	    ])
	?>

</div>
