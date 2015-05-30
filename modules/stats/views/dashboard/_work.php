<?php
use app\models\Document;
use yii\helpers\Url;
?>
<div class="dashboard-work">

<h4><?= Yii::t('store', 'Work') ?></h4>

<div class="row">
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'Today', 'date' => Document::DATE_TODAY]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'Tomorrow', 'date' => Document::DATE_NEXT]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'Next week', 'date' => Document::DATE_NEXT_WEEK]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'Late', 'date' => Document::DATE_LATE]) ?>
	</div>
</div>

</div>
