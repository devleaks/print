<?php

use app\models\Document;
use app\models\User;
use app\models\Work;
use kartik\helpers\Html as KHtml;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/order']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->document_type, true)), 'url' => ['/order/document/'.strtolower($model->document_type).'s', 'sort' => '-updated_at']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

	<?= $this->render('_header_view', [
			'model' => $model,
	    ]);
	?>

	<?= $this->render('../document-line/_list', [
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getDocumentLines()
			]),
			'order' => $model,
			'action_template' => User::hasRole(['admin', 'manager']) ? '{view} {delete}' : '{view}',
	    ]);
	?>

</div>
