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
		<?= $this->render('_work_line', ['title' => 'Next', 'date' => Document::DATE_NEXT]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'Next week', 'date' => Document::DATE_NEXT_WEEK]) ?>
	</div>
	<div class="col-lg-3">
		<?= $this->render('_work_line', ['title' => 'All', 'date' => null]) ?>
	</div>
</div>

</div>
