<?php
use yii\helpers\Url;

$bg = '';
if($pic = $model->getPictures()->one()) {
	$bg = 'background-image:url('.$pic->getThumbnailUrl().');';
	$bg .= 'background-size: '.$model->work_width.'px '.$model->work_height.'px;';
	$bg .= 'background-repeat: no-repeat;';
}

?>
<div class="item"
	style="width: <?= $model->work_width ?>px; height: <?= $model->work_height ?>px;<?= $bg ?>"
	title="<?= $model->document->name ?>">
	<a href="<?= Url::to(['/order/document/view', 'id' => $model->document->id]) ?>" style="text-decoration:none; color: black;"><?= $model->work_width.' &times; '.$model->work_height.'<br/>'.$model->quantity ?></a>
</div>