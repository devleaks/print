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
		    <h1>Articles
		      <span>
		        <span class="filter-count"></span>
		         lignes de ventes sélectionnées parmi  
		        <span class="total-count"></span>
		         lignes de ventes | 
		        <a href="javascript:dc.filterAll(); dc.renderAll();">Restaurer tout</a>
		      </span>
		    </h1>
		</div><!--.span12-->
		
	</div><!--.row-->
	

	<div class="row">
		<div class="col-lg-3" id="years">			
			<h4>
				Année
		        <span>
		          <a class="reset"
		            href="javascript:yearChart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
		<div class="col-lg-3" id="types">
			<h4>
				Types de documents
		        <span>
		          <a class="reset"
		            href="javascript:typeChart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
		<div class="col-lg-3" id="cat1">
			<h4>
				Catégorie
		        <span>
		          <a class="reset"
		            href="javascript:cat1Chart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
		<div class="col-lg-3" id="cat2">
			<h4>
				Catégorie (2)
		        <span>
		          <a class="reset"
		            href="javascript:cat2Chart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
	</div><!--.row-->


	<div class="row">
		
		<div class="col-lg-12" id="salesStack">
			<h4>
				Ventes par jour (par mois)
		        <span>
		          <a class="reset"
		            href="javascript:salesStackChart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.span12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12" id="sales">
			<h2>
				Ventes par jour (détail)
		        <span>
		          <a class="reset"
		            href="javascript:salesChart.filterAll();dc.redrawAll();"
		            style="display: none;">reset</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.span12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12">
			<div><h2>Articles</h2>
			<table id="items" class="table">
				<thead>
				<tr class="header">
					<th>Article</th>
					<th>Total</th>
				</tr>
				</thead>
			</table>
			</div>
		</div><!--.span12-->
		
	</div><!--.row-->

</div><!--.container -->
<script type="text/javascript">
<?php $this->beginBlock('JS_DC_SALES'); ?>

var url = "<?= Url::to(['/stats/bi-line'],['_format' => 'json']) ?>";
// count all the facts
var numberFormat = BE.numberFormat("$,.2f");
var typeList = ["BID","ORDER","BILL","CREDIT","TICKET","REFUND"];
var docType = {
	ORDER: "Commande",
	BILL: "Facture",
	BID: "Offre",
	TICKET: "VC",
	REFUND: "Remb",
	CREDIT: "NC"
};
var docTypeColor = {
	"Commande": "darkgreen",
	"Facture": "green",
	"Offre": "lightblue",
	"VC": "blue",
	"Remb": "orange",
	"NC": "red"
};

var colors = [];
var colorIndices = [];
for (var t in docTypeColor) {
    if (docTypeColor.hasOwnProperty(t)) {
		colors.push(docTypeColor[t]);
		colorIndices.push(t);
    }
}
	
var salesChart = dc.barChart("#sales");
var salesStackChart = dc.barChart("#salesStack");
var yearChart = dc.pieChart("#years");
var typeChart = dc.rowChart("#types");
var cat1Chart = dc.rowChart("#cat1");
var cat2Chart = dc.rowChart("#cat2");
var dataTable = dc.dataTable("#items");

d3.json(url, function(error, data) {

	/**{
		"document_type":"TICKET",
		"document_name":"2017-A-0675",
		"created_at":"2017-06-02T15:49:53Z",
		"work_width":24,
		"work_height":35,
		"unit_price":"41.00",
		"quantity":1,
		"extra_type":"",
		"extra_amount":null,
		"extra_htva":null,
		"total_htva":"41.00",
		"total_htva":"41.00",
		"item_name":"ChromaLuxe",
		"categorie":"ChromaLuxe",
		"yii_category":"ChromaLuxe",
		"comptabilite":"700200"
	}*/

	data.forEach(function(sale) {
		sale.created_at = Date.parse(sale.created_at.replace(' ', 'T'));
		sale.total_htva = +sale.total_htva;

		sale.document_type = typeof(docType[sale.document_type]) != 'undefined' ? docType[sale.document_type] : sale.document_type;
		if(! sale.categorie) sale.categorie = "Sans";
		if(! sale.yii_category) sale.yii_category = "Sans";

		var c=new Date(sale.created_at);
		sale.period = new Date(c.getFullYear(),c.getMonth(),c.getDate(),0,0,0,0);
		sale.period_month = new Date(c.getFullYear(),c.getMonth(),1,0,0,0,0);
		sale.year = c.getFullYear();
	})

	var ndx = crossfilter(data);
	var all = ndx.groupAll();

	var dateDim = ndx.dimension(function(d) { return d.period; });
	var totals = dateDim.group().reduceSum(function(d) {return d.total_htva;});

	var monthDim = ndx.dimension(function(d) { return d.period_month; });
	var monthTotals = monthDim.group().reduceSum(function(d) {return d.total_htva;});

	var minDate = dateDim.bottom(1)[0].period_month;
	var maxDate = dateDim.top(1)[0].period_month;
	//console.log(minDate, maxDate)
	dc.dataCount(".dc-data-count")
	  .dimension(ndx)
	  .group(all);

	var typeDim = ndx.dimension(function(d) { return d.document_type; });
	var type_total = typeDim.group().reduceSum(function(d) {return d.total_htva;});
	
	var yearDim  = ndx.dimension(function(d) {return d.year;});
	var year_total = yearDim.group().reduceSum(function(d) {return d.total_htva;});
	
	var cat1Dim  = ndx.dimension(function(d) {return d.categorie});
	var cat1_total = cat1Dim.group().reduceSum(function(d) {return d.total_htva;});
	
	var cat2Dim  = ndx.dimension(function(d) {return d.yii_category;});
	var cat2_total = cat2Dim.group().reduceSum(function(d) {return d.total_htva;});
	
	var itemDim  = ndx.dimension(function(d) {return d.item_name;});
	var item_total = itemDim.group().reduceSum(function(d) {return d.total_htva;});
	
	// sum group by document_type
	var typeSumGroup = monthDim.group().reduce(
		function(p, v) {
			p[v.document_type] = (p[v.document_type] || 0) + v.total_htva;
			return p;
		},
		function(p, v) {
			p[v.document_type] = (p[v.document_type] || 0) - v.total_htva;
			return p;
		},
		function() {
			var e = {};
			//["BID","ORDER","BILL","CREDIT","TICKET","REFUND"].forEach(function(t){e[t]=0;})
			for (var t in docType) {
			    if (docType.hasOwnProperty(t)) {
					e[docType[t]]=0;
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
		
	var ssinit = "ORDER";
	salesStackChart
		.width(1000).height(200)
		.x(d3.time.scale().domain([minDate,maxDate]))
        .margins({left: 50, top: 0, right: 50, bottom: 0})
        .xUnits(d3.time.months)
        .dimension(monthDim)
        .group(typeSumGroup, "Commande")
		.elasticY(true)
		.renderHorizontalGridLines(true)
		.ordinalColors(colors)
		.valueAccessor(function (d) {
			return d.value["Commande"];
		});
  	//console.log("stacking "+docType[ssinit]);

	for (var t in docType) {
	    if (t != ssinit && docType.hasOwnProperty(t)) {
			//console.log("stacking "+docType[t]);
			salesStackChart.stack(typeSumGroup, docType[t], sel_stack(docType[t]));
	    }
	}
	
	// salesStackChart.legend(dc.legend());

    dc.override(salesStackChart, 'legendables', function() {
        var items = salesStackChart._legendables();
        return items.reverse();
    });
    
	
	salesChart
		.width(1000).height(200)
        .margins({left: 50, top: 0, right: 50, bottom: 0})
		.dimension(dateDim)
		.group(totals)
		.x(d3.time.scale().domain([minDate,maxDate]))
		.elasticY(true)
		.renderHorizontalGridLines(true)
        .xUnits(d3.time.days)
		.turnOnControls(true);

	yearChart
	    .width(150).height(150)
	    .dimension(yearDim)
	    .group(year_total)
	    .innerRadius(30)
		.turnOnControls(true);
		
	typeChart
	    .width(250).height(150)
        .margins({left: 0, top: 0, right: 100, bottom: 0})
	    .dimension(typeDim)
		.ordinalColors(colors)
		.colorAccessor(function (d, i){/*console.log(d, colorIndices.indexOf(d.key), i);*/return colorIndices.indexOf(d.key);})
	    .group(type_total)
		.turnOnControls(true);

	cat1Chart
	    .width(250).height(150)
        .margins({left: 0, top: 0, right: 100, bottom: 0})
	    .dimension(cat1Dim)
	    .group(cat1_total)
		.turnOnControls(true);

	cat2Chart
	    .width(250).height(150)
        .margins({left: 0, top: 0, right: 100, bottom: 0})
	    .dimension(cat2Dim)
	    .group(cat2_total)
		.turnOnControls(true);

	dataTable
		.dimension(item_total)
		.group(function(d) { return "Articles satisfaisant les critères sélectionnés, classés par chiffre d'affaire." })
		.size(10) // number of rows to return
		.columns([
		        function (d) {
		            return d.key;
		        },
				function (d) {
		            return numberFormat(d.value);
		        }

		    ])
		.sortBy(function(d){ return d.value; })
	    .order(d3.descending);

	dc.filterAll();
    dc.renderAll();

	d3.selectAll('#version').text(dc.version);		
	d3.json('https://api.github.com/repos/dc-js/dc.js/releases/latest', function (error, latestRelease) {
	    /*jshint camelcase: false */
	    /* jscs:disable */
	    d3.selectAll('#latest').text(latestRelease.tag_name);
	});
});		
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_DC_SALES'], yii\web\View::POS_END);
