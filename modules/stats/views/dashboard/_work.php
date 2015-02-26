<?php
use app\models\Document;
use yii\helpers\Url;
?>
<div class="dashboard-work">

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
		<?= $this->render('_work_line', ['title' => 'Conversion', 'date' => Document::DATE_NEXT_WEEK]) ?>
	</div>
</div>

</div>
