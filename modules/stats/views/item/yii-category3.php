<?php

use kartik\helpers\Enum;

use yii2mod\c3\chart\Chart;
use yii2mod\c3\chart\ChartAsset;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

ChartAsset::register($this);

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

	<?php  VarDumper::dumpAsString($datadd, 4, true) ?>
	<?php  VarDumper::dumpAsString($groups2, 4, true) ?>
	
	<h2>Quantités</h2>
	
	<div class="row">

		<div class="col-lg-6">
			<div id="c3chart"></div>
			<?= Chart::widget([
				'options' => [
			        'id' => 'c3chart'
				],
				'clientOptions' => [
					'data'=> [
				        'columns' => $groups,
						'type' => 'pie',
						'onclick' => new JsExpression('function (d, element) {
								c3.generate({
									bindto: "#c3chartdd",
									data: {
										columns: datadd[d["name"]],
										type: "pie"
									},
									legend: {
										show: false
									},
									pie: {
										label: {
											format: function (value, ratio, id) { return id; }
										}
									},
								});
							}')
				    ],
					'pie' => [
						'label' => [
							'format' => new JsExpression('function (value, ratio, id) { return id; }')
						]
					]
			    ]
			]);?>
		</div>


		<div class="col-lg-6">
			<div id="c3chartdd"></div>
		</div>
	</div>
	
	<h2>Chiffre d'affaire</h2>
	
	<div class="row">

		<div class="col-lg-6">
			<div id="c3chart2"></div>
			<?= Chart::widget([
				'options' => [
			        'id' => 'c3chart2'
				],
				'clientOptions' => [
					'data'=> [
				        'columns' => $groups2,
						'type' => 'pie',
						'onclick' => new JsExpression('function (d, element) {
								c3.generate({
									bindto: "#c3chart2dd",
									data: {
										columns: datadd2[d["name"]],
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
							}')
				    ],
					'pie' => [
						'label' => [
							'format' => new JsExpression('function (value, ratio, id) { return id; }')
						]
					]
			    ]
			]);?>
		</div>


		<div class="col-lg-6">
			<div id="c3chart2dd"></div>
		</div>
	</div>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_C3_DRILLDOWN'); ?>
var BE = d3.locale ({
	  "decimal": ",",
	  "thousands": ".",
	  "grouping": [3],
	  "currency": ["", " €"],
	  "dateTime": "%a %b %e %X %Y",
	  "date": "%d/%m/%Y",
	  "time": "%H:%M:%S",
	  "periods": ["AM", "PM"],
	  "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
	  "shortDays": ["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],
	  "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
	  "shortMonths": ["Janv", "Févr", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
	});
var datadd = <?= json_encode($datadd)?>;
var datadd2 = <?= json_encode($datadd2)?>;

<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_C3_DRILLDOWN'], yii\web\View::POS_END);
