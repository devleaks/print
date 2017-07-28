<?php

use yii\helpers\Url;
use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;
use app\assets\BeAsset;

ChartAsset::register($this);
BeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div id="lately" class="dashboard-lately">
</div>

<script type="text/javascript">
<?php $this->beginBlock('JS_LATELY'); ?>
$(function() {
    $.getJSON("<?= Url::to(['by-month']) ?>", function(data) {
		if(data.length > 0) {
			var c3data = [];
			var c3group = {};
			data.forEach(function(d) {
				var yr = [];
				yr.push(d['name']);
				for(var i = 0; i < 12; i++) {
					yr.push( ((typeof d['data'][i] != "undefined") && (typeof d['data'][i]['y'] != "undefined")) ? d['data'][i]['y'] : 0);					
				}
				c3data.push(yr);
				if(typeof c3group[d['stack']] == "undefined")
					c3group[d['stack']] = []
				c3group[d['stack']].push(d['name']);
			});
			var c3grarr = [];
			for (var g in c3group) {
			    if (c3group.hasOwnProperty(g)) {
					c3grarr.push(c3group[g]);
			    }
			}
			var chart = c3.generate({
				bindto: '#lately',
				data: {
					columns: c3data,
					groups: c3grarr,
					type: 'bar'
				},
				axis: {
					x: {
						type: 'category',
						categories: ["Jan", "Fév", "Mars", "Avr", "Mai", "Juin", "Jul", "Août", "Sep", "Oct", "Nov", "Déc"]
					},
					y: {
						label: "Chiffre d'affaire (€)"
					}
				}
			});			
		} else {
			$("#lately").html("<?= Yii::t('store', 'No data.') ?>");
		}
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_LATELY'], yii\web\View::POS_READY);
