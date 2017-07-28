<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;
use app\assets\BeAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);
BeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// 1. QTY
$catdata = [];
$subdata = [];
foreach($dataProvider->query->each() as $m) {
	$cat = $m['category'] == ''? 'Sans' :$m['category'];
	if(!isset($catdata[$cat])) {
		$catdata[$cat] = 0;
		$subdata[$cat] = [];
	}
	$catdata[$cat] += intval($m['tot_count']);
	$subdata[$cat][] = [$m['name'] == '' ? 'Sans' : $m['name'], intval($m['tot_count'])];
}

$groups = [];
foreach($catdata as $cat => $val) {
	$groups[] = [$cat, $val];
}

$datadd = [];
foreach($subdata as $cat => $val) {
	$subgroup = [];
	foreach($val as $item => $qty) {
		$subgroup[] = $qty;
	}
	$datadd[$cat] = $subgroup;
}
// 2. CA
$catdata2 = [];
$subdata2 = [];
foreach($dataProvider2->query->each() as $m) {
	$cat = $m['category'] == ''? 'Sans' :$m['category'];
	if(!isset($catdata2[$cat])) {
		$catdata2[$cat] = 0;
		$subdata2[$cat] = [];
	}
	$catdata2[$cat] += intval($m['tot_count']);
	$subdata2[$cat][] = [$m['name'] == '' ? 'Sans' : $m['name'], intval($m['tot_count'])];
}

$groups2 = [];
foreach($catdata2 as $cat => $val) {
	$groups2[] = [$cat, $val];
}

$datadd2 = [];
foreach($subdata2 as $cat => $val) {
	$subgroup = [];
	foreach($val as $item => $qty) {
		$subgroup[] = $qty;
	}
	$datadd2[$cat] = $subgroup;
}

$this->title = Yii::t('store', 'Items').' — Catégorie Spéciale';
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php  VarDumper::dumpAsString($datadd, 4, true) ?>
	<?php  VarDumper::dumpAsString($groups2, 4, true) ?>
	
	<h2>Quantités</h2>
		
	<div style="text-align:center;">
			<h4 id="details"></h4>
			<a id="btnShowBar" style="display:none;">&larr;&nbsp;Retour aux catégories</a>
	</div>
	
	<div class="row">

		<div class="col-lg-12">
			
			<div id="c3chart"></div>
		</div>

	</div>
	
	<h2>Chiffre d'affaire</h2>
	
	<div class="row">

		<div class="col-lg-12">
			<div id="c3chart2"></div>
		</div>
	</div>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_C3_DRILLDOWN'); ?>
var datadd = <?= json_encode($datadd)?>;
var datadd2 = <?= json_encode($datadd2)?>;
var groups = <?= json_encode($groups)?>;
var groups2 = <?= json_encode($groups2)?>;
function generateFor(name) {
	d3.select("#details").html('Détails pour « '+name+' »');
	c3.generate({
		bindto: "#c3chart",
		data: {
			columns: datadd[name],
			type: "pie"
		},
		pie: {
			label: {
				format: function (value, ratio, id) { return id; }
			}
		},
		legend: {
			show: false
		}
	});
	c3.generate({
		bindto: "#c3chart2",
		data: {
			columns: datadd2[name],
			type: "pie"
		},
		pie: {
			label: {
				format: function (value, ratio, id) { return id; }
			}
		},
		tooltip: {
			format: {
				value: function (value, ratio, id, index) { var format = BE.numberFormat("$,"); return format(value); }
			}
		},
		legend: {
			show: false
		}
	});
	d3.select("#btnShowBar").style("display","inline");
}
function generateBarChart() {
	d3.select("#details").html('');
    c3.generate({
		bindto: '#c3chart',
        data: {
            columns: groups,
            type: 'pie',
            onclick: function (d, element) {
				generateFor(d["name"]);
			}
        },
		pie: {
			label: {
				format: function (value, ratio, id) { return id; }
			}
		},
    });
    c3.generate({
		bindto: '#c3chart2',
        data: {
            columns: groups2,
            type: 'pie',
            onclick: function (d, element) {
				generateFor(d["name"]);
			}
        },
		pie: {
			label: {
				format: function (value, ratio, id) { return id; }
			}
		},
		tooltip: {
			format: {
				value: function (value, ratio, id, index) { var format = BE.numberFormat("$,"); return format(value); }
			}
		}
    });
}
function showBarChart() {
    chart = generateBarChart();
    d3.select('#btnShowBar').style('display', 'none');
}
d3.select('#btnShowBar').on('click', showBarChart);
generateBarChart();
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_C3_DRILLDOWN'], yii\web\View::POS_END);
