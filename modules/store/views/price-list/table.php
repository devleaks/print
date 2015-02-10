<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PriceListItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Composite Price Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-list-item-view">

	<?= $content ?>

	<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
	 	<span class="glyphicon glyphicon-print"></span> <?= Yii::t('store', 'Print') ?> <span class="caret"></span></button><ul class="dropdown-menu" role="menu">
						<li><?= Html::a(Yii::t('store', 'Portrait'),  ['print', 'id' => $model->id, 'format' => 'P'], ['target' => '_blank', 'title' => Yii::t('store', 'Portrait')]) ?></li>
						<li><?= Html::a(Yii::t('store', 'Landscape'), ['print', 'id' => $model->id, 'format' => 'L'], ['target' => '_blank', 'title' => Yii::t('store', 'Landscape')]) ?></li>
		</ul>
	</div>
</div>
