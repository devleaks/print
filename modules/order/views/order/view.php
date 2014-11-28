<?php

use app\models\Document;
use app\models\Work;
use kartik\helpers\Html as KHtml;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->order_type, true)), 'url' => [strtolower($model->order_type).'s']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

	<?= $this->render('_header_view', [
			'model' => $model,
	    ]);
	?>

	<?= $this->render('../order-line/_list', [
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getOrderLines()
			]),
			'order' => $model,
			'action_template' => '{view}'
	    ]);
	?>

</div>
