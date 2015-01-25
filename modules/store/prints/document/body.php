<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
$this->title = Yii::t('print', $model->document_type) . ' ' . $model->name;

$lang = ($model->client->lang ? $model->client->lang : 'fr');
Yii::$app->language = $lang;
$use_gridview = false;
?>
<div class="document-print-body">

	<?= $this->render('header', [
			'model' => $model,
	    ])
	?>

	<?= $this->render('table', [ // 'table-gv'
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getDocumentLines(),
					'pagination' => false,
			]),
			'order' => $model
	    ])
	?>

	<?= $this->render('footer', [
			'model' => $model,
	    ])
	?>

</div>
