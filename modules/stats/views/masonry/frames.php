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

if($max == 0) $max = 1;

$color_level = 10;
$color_start = 40;
$color_lut = [];
for($i = 0; $i <= $color_level; $i++) {
	$c = round($color_start + $i * (256 - $color_start)/$color_level);
	$color_lut[$i] = 'rgb('.$c.','.$c.','.$c.')';
}
$thick_level = 4;
?>
<div class="jjm-graphic container">

    <h1><?= Html::encode($this->title)?></h1>

<div id="container"  class="jjm-frame-container" style="width: <?= $max_width * $factor ?>px; height: <?= $max_height * $factor ?>px; position: relative;">
<?php
	for($i = $max_dim; $i > 0; $i-=$step): ?>
		<div class="jjm-frame-ref"
			style="width: <?= $i * $factor ?>px; height: <?= $i * $factor ?>px;"
			title="<?= $i.'&times;'.$i ?>"><div class="jjm-frame-ref-label"><?= $i ?></div>
		</div>
<?php endfor; ?>
<?php
	foreach($dataProvider->query->each() as $frame): ?>
		<div class="jjm-frame"
			style="width: <?= $frame['width'] * $factor ?>px; height: <?= $frame['height'] * $factor ?>px; border: <?= 1 + intval(3*$frame['tot_count']/$max) ?>px solid <?= $color_lut[intval($color_level-($frame['tot_count']*$color_level/$max))] ?>;"
			title="<?= $frame['width'].'&times;'.$frame['height'].'&times;'.$frame['tot_count'] ?>">
		</div>
<?php endforeach; ?>
</div>

<div class="jjm-frame-swatches"style="width: <?= $max_width * $factor ?>px;">
<?php
	echo Yii::t('store', 'Quantités').' ';
	for($i = 1; $i <= $color_level; $i++): ?>
		<div class="jjm-frame-swatch"
			style="vertical-align: middle;width: 40px; height: 40px;border: <?= 1 + intval(3*$i/$color_level) ?>px solid <?= $color_lut[intval($color_level-($i))] ?>;"
			title="<?= $i.'&times;'.$i ?>">
			<?= round($i*$max/$color_level) ?>
		</div>
<?php endfor; ?>
</div>

</div>
