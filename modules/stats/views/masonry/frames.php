<?php

use devleaks\metafizzy\PackeryAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('store', 'Tailles demandées');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination = false;

$factor = 4;
$max_width = 220;
$max_height = 220;
$max_dim = max($max_width, $max_height);
$step = 20;
?>
<div class="jjm-graphic">

    <h1><?= Html::encode($this->title) ?></h1>

<div id="container"  class="jjm-frame-container" style="width: <?= $max_width * $factor ?>px; height: <?= $max_height * $factor ?>px; position: relative;">
<?php
	for($i = $max_dim; $i > 0; $i-=$step): ?>
		<div class="jjm-frame-ref"
			style="width: <?= $i * $factor ?>px; height: <?= $i * $factor ?>px;"
			title="<?= $i.'&times;'.$i ?>">
		</div>
<?php endfor; ?>
<?php
	foreach($dataProvider->query->each() as $frame): ?>
		<div class="jjm-frame"
			style="width: <?= $frame->work_width * $factor ?>px; height: <?= $frame->work_height * $factor ?>px;"
			title="<?= $frame->document->name.': '.$frame->work_width.'&times;'.$frame->work_height.'&times;'.$frame->quantity ?>">
		</div>
<?php endforeach; ?>
</div>

</div>
