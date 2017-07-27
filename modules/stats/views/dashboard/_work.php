<?php
use app\models\Document;
use yii\helpers\Url;
?>
<div class="dashboard-work">

<h4><?= Yii::t('store', 'Works') ?></h4>

<div class="row">
	<div class="col-lg-3">
		<?= $this->render('_work_line3', ['title' => 'Today', 'date' => Document::DATE_TODAY]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line3', ['title' => 'Tomorrow', 'date' => Document::DATE_NEXT]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line3', ['title' => 'Next week', 'date' => Document::DATE_NEXT_WEEK]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line3', ['title' => 'Late', 'date' => Document::DATE_LATE]) ?>
	</div>
</div>

</div>
