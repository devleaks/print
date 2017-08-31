<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\assets\BiAsset;
use app\assets\BeAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

BiAsset::register($this);
BeAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	
	<div class="row">
		
		<div class="col-lg-12 dc-data-count" style="float: left;">
		    <h1><?= Html::encode($this->title) ?>
		      <span>
		        <span class="filter-count"></span>
		         tâches sélectionnées parmi  
		        <span class="total-count"></span>
		         tâches | 
		        <a href="javascript:dc.filterAll(); dc.renderAll();">Annuler toutes les sélections</a>
		      </span>
		    </h1>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->
	

	<div class="row">
		<div class="col-lg-3" id="types">
			<h4>
				Types de documents
		        <span>
		          <a class="reset"
		            href="javascript:typeChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="items">			
			<h4>
				Article
		        <span>
		          <a class="reset"
		            href="javascript:itemChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="tasks">
			<h4>
				Tâches
		        <span>
		          <a class="reset"
		            href="javascript:taskChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="duras">
			<h4>
				Durée de réalisation (jours)
		        <span>
		          <a class="reset"
		            href="javascript:duraChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
	</div><!--.row-->


	<div class="row">
		
		<div class="col-lg-12" id="sales">
			<h2>
				Tâches accomplies par jour
		        <span>
		          <a class="reset"
		            href="javascript:salesChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12">
			<div><h2>Tâches</h2>
			<table id="clients" class="table">
				<thead>
				<tr class="header">
					<th>Catégorie</th>
					<th>Article</th>
					<th>Tâche</th>
					<th>Quantité</th>
				</tr>
				</thead>
			</table>
			</div>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->

</div><!--.container -->
<script type="text/javascript">
<?php $this->beginBlock('JS_DC_WORKS'); ?>

var url = "<?= Url::to(['/stats/bi-work'],['_format' => 'json']) ?>";

var numberFormat = BE.numberFormat(",.2f");
var dateFormat = BE.timeFormat("%b %Y");

dc.dateFormat = BE.timeFormat("%b %Y");

var salesChart = dc.barChart("#sales");
var salesStackChart = dc.barChart("#salesStack");
var itemChart = dc.pieChart("#items");
var typeChart = dc.rowChart("#types");
var taskChart = dc.pieChart("#tasks");
var duraChart = dc.barChart("#duras");
var dataTable = dc.dataTable("#clients");
	
d3.json(url, function(error, data) {
/**
{
	"document_type":"TICKET",
	"document_status":"CLOSED",
	"document_name":"2015-A-1060",
	"created_at":"2015-01-28 15:24:23",
	"updated_at":"2015-10-05 10:11:50",
	"total_price_htva":"539.00",
	"document_line":1,
	"line_price_htva":null,
	"line_item_name":"ChromaLuxe",
	"item_categorie":"ChromaLuxe",
	"item_yii_category":"ChromaLuxe",
	"work_item_name":"ChromaLuxe",
	"task_name":"Découpe",
	"work_status":"DONE",
	"work_line_status":"DONE",
	"position":100,
	"date_start":"2015-01-28 15:24:40",
	"date_finish":"2015-01-29 15:34:39",
	"duration":1000999
}
*/
	var cnt = 0;

	data.forEach(function(d) {
		d.created_at = Date.parse(d.created_at.replace(' ', 'T'));
		d.updated_at = Date.parse(d.updated_at.replace(' ', 'T'));

		d.date_start = Date.parse(d.date_start.replace(' ', 'T'));
		d.date_finish = Date.parse(d.date_finish.replace(' ', 'T'));

		d.total_price_htva = +d;
		d.line_price_htva = +d;
		d.duration = +d.duration;

		var c=new Date(d.created_at);
		d.period = new Date(c.getFullYear(),c.getMonth(),c.getDate(),0,0,0,0);
		d.period_month = new Date(c.getFullYear(),c.getMonth(),1,0,0,0,0);

		d.document_type = typeof(docTypes[d.document_type]) != 'undefined' ? docTypes[d.document_type]['label'] : d.document_type;

	})

	var ndx = crossfilter(data);
	var all = ndx.groupAll();

	var dateDim = ndx.dimension(function(d) { return d.period; });
	var totals = dateDim.group().reduceCount();

	var monthDim = ndx.dimension(function(d) { return d.period_month; });
	var monthTotals = monthDim.group().reduceCount();

	var minDate = dateDim.bottom(1)[0].period_month;
	var maxDate = dateDim.top(1)[0].period_month;
	//console.log(minDate, maxDate)
	dc.dataCount(".dc-data-count")
	  .dimension(ndx)
	  .group(all);

	var typeDim = ndx.dimension(function(d) { return d.document_type; });
	var type_total = typeDim.group().reduceCount();
	
	var itemDim  = ndx.dimension(function(d) {return d.line_item_name;});
	var item_total = itemDim.group().reduceCount();
	
	var taskDim  = ndx.dimension(function(d) {return d.task_name;});
	var task_total = taskDim.group().reduceCount();
																		//  86400000
	var duraDim  = ndx.dimension(function(d) {var r = Math.floor(d.duration/86400); return r > 15 ? 15 : r;});
	var dura_total = duraDim.group().reduceCount();
	
	var catgDim  = ndx.dimension(function(d) {return d.item_categorie;});
	var catg_total = catgDim.group().reduceCount();
	
	var cliDim  = ndx.dimension(function(d) {return [d.item_categorie, d.line_item_name, d.task_name];});
	var cli_total = cliDim.group().reduceCount(); // Sum(function(d) {return d.duration;});
	
	// sum group by document_type
	var typeSumGroup = monthDim.group().reduce(
		function(p, v) {
			p[v.document_type] = (p[v.document_type] || 0) + v.price_htva;
			return p;
		},
		function(p, v) {
			p[v.document_type] = (p[v.document_type] || 0) - v.price_htva;
			return p;
		},
		function() {
			var e = {};
			//["BID","ORDER","BILL","CREDIT","TICKET","REFUND"].forEach(function(t){e[t]=0;})
			for (var t in docTypes) {
			    if (docTypes.hasOwnProperty(t)) {
					e[docTypes[t]['label']]=0;
			    }
			}
            return e;
		}
	);

	function sel_stack(i) {
		return function(d) {
			return d.value[i];
		};
	}
		
	typeChart
	    .width(250).height(150)
	    .dimension(typeDim)
        .margins({left: 0, top: 0, right: 100, bottom: 0})
		.colors(docTypesColors)
	    .group(type_total)
		.turnOnControls(true);

	itemChart
	    .width(150).height(150)
	    .dimension(itemDim)
	    .group(item_total)
	    .innerRadius(30)
		.turnOnControls(true);

	taskChart
	    .width(150).height(150)
	    .dimension(taskDim)
	    .group(task_total)
	    .innerRadius(30)
		.turnOnControls(true)
		.minAngleForLabel(Math.PI / 20);

	duraChart
	    .width(250).height(150)
	    .dimension(duraDim)
	    .group(dura_total)
		.x(d3.scale.linear().domain([0,15]))
        .margins({left: 40, top: 0, right: 0, bottom: 20})
//	    .innerRadius(30)
		.turnOnControls(true)
//		.minAngleForLabel(Math.PI / 40);

	salesChart
		.width(1140).height(200)
		.dimension(dateDim)
		.group(totals)
		.x(d3.time.scale().domain([minDate,maxDate]))
        .xUnits(d3.time.days)
		.elasticY(true)
		.renderHorizontalGridLines(true)
        .margins({left: 50, top: 0, right: 50, bottom: 20})
		.turnOnControls(true);

	var ssinit = "ORDER";
	salesStackChart
		.width(1140).height(200)
		.x(d3.time.scale().domain([minDate,maxDate]))
        .margins({left: 50, top: 0, right: 50, bottom: 20})
		.elasticY(true)
        .xUnits(d3.time.months)
        .dimension(monthDim)
        .group(typeSumGroup, "Commande")
		.colors(docTypesColors)
		.valueAccessor(function (d) {
			return d.value["Commande"];
		});

	for (var t in docTypes) {
	    if (t != ssinit && docTypes.hasOwnProperty(t)) {
			//console.log("stacking "+docTypes[t]);
			salesStackChart.stack(typeSumGroup, docTypes[t]['label'], sel_stack(docTypes[t]['label']));
	    }
	}

    dc.override(salesStackChart, 'legendables', function() {
        var items = salesStackChart._legendables();
        return items.reverse();
    });


	dataTable
		.dimension(cli_total)
		.group(function(d) { return "Tâches qui prennent le plus de temps, classées par ordre décroissant de temps cumulé." })
		.size(10) // number of rows to return
		.columns([
		        function (d) {
		            return d.key[0];
		        },
		        function (d) {
		            return d.key[1];
		        },
		        function (d) {
		            return d.key[2];
		        },
				function (d) {
					return d.value;
					var secs = d.value / 100000;
					var yrs = Math.floor(secs / (3600*24*365.25));
					secs -= yrs * (3600*24*365.25);
					var days = Math.floor(secs / (3600*24));
					secs -= days * (3600*24);
					var hrs  = Math.floor(secs / 3600);
					secs -= hrs * 3600;
					var mnts = Math.floor(secs / 60);
					secs -= mnts * 60;
		            return yrs+'y '+days+'d '+hrs+'h '+mnts+'m '+Math.floor(secs)+'s';
		        }

		    ])
		.sortBy(function(d){ return d.value; })
	    .order(d3.descending);

	dc.filterAll();	
    dc.renderAll();
});	
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_DC_WORKS'], yii\web\View::POS_END);
