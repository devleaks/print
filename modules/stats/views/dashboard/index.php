<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Dashboard');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard-index">

<div class="row">
	<div class="col-lg-4">
		<?= $this->render('_today') ?>
	</div>
	<div class="col-lg-8">
		<?= $this->render('_lately') ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<?= $this->render('_work') ?>
	</div>
	<div class="col-lg-4">
		<?= $this->render('_misc') ?>
	</div>
</div>

</div>
