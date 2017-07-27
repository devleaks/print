<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;

ChartAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<h5><?= Html::encode(Yii::t('store', $title)) ?></h5>
<div id="workline<?= $date ?>" class="dashboard-work-line">
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_WORKLINE_CHART'.$date); ?>
$(function() {
    $.getJSON("<?= Url::to(['work-lines', 'id' => $date]) ?>", function(data) {
		if(data.length > 0) {
			var c3data = [], c3colr = [];
			data.forEach(function(d) {
				c3data.push([d['name'], d['y']]);
				c3colr.push([d['name'], d['color']]);
			});			
			var chart = c3.generate({
				bindto: "#workline<?= $date ?>",
				data: {
					columns: c3data,
					type: 'donut',
					colors: c3colr
				}
			});			
			
			
		} else {
			$("#workline<?= $date ?>").html("<?= Yii::t('store', 'No data.') ?>");
		}
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_WORKLINE_CHART'.$date], yii\web\View::POS_END);
