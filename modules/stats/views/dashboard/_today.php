<?php
use yii\helpers\Url;
?>
<div class="dashboard-today">

<div class="row">
	<div class="col-lg-6">
		<?= $this->render('_document', ['title' => 'Today']) ?>
	</div>
	<div class="col-lg-6">
		<?= Yii::t('store', '2') ?>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<?= Yii::t('store', '3') ?>
	</div>
	<div class="col-lg-6">
		<?= Yii::t('store', '4') ?>
	</div>
</div>

</div>
