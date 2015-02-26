<?php
use yii\helpers\Url;
?>
<div class="item" style="width: <?= $model->work_width ?>px; height: <?= $model->work_height ?>px;" title="<?= $model->document->name ?>">
	<?= $model->work_width.' &times; '.$model->work_height.'<br/>'.$model->quantity ?>
</div>