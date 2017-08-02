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
		         ventes sélectionnées parmi  
		        <span class="total-count"></span>
		         ventes | 
		        <a href="javascript:dc.filterAll(); dc.renderAll();">Supprimer tous les filtres</a>
		      </span>
		    </h1>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->
	

	<div class="row">
		<div class="col-lg-3" id="years">			
			<h4>
				Année
		        <span>
		          <a class="reset"
		            href="javascript:yearChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="types">
			<h4>
				Types de documents
		        <span>
		          <a class="reset"
		            href="javascript:typeChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="langs">
			<h4>
				Langue
		        <span>
		          <a class="reset"
		            href="javascript:langChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
		<div class="col-lg-3" id="cntrs">
			<h4>
				Pays
		        <span>
		          <a class="reset"
		            href="javascript:cntrChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.col-lg-3-->
		
	</div><!--.row-->


	<div class="row">
		
		<div class="col-lg-12" id="salesStack">
			<h2>
				Ventes par mois
		        <span>
		          <a class="reset"
		            href="javascript:salesStackChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12" id="sales">
			<h2>
				Ventes par jour
		        <span>
		          <a class="reset"
		            href="javascript:salesChart.filterAll();dc.redrawAll();"
		            style="display: none;">supprimer</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12">
			<div><h2>Clients</h2>
			<table id="clients" class="table">
				<thead>
				<tr class="header">
					<th>Client</th>
					<th>Pays</th>
					<th>Langue</th>
					<th>Total</th>
				</tr>
				</thead>
			</table>
			</div>
		</div><!--.col-lg-12-->
		
	</div><!--.row-->

</div><!--.container -->
<script type="text/javascript">
<?php $this->beginBlock('JS_DC_SALES'); ?>

var url = "<?= Url::to(['/stats/bi-sale'],['_format' => 'json']) ?>";

var numberFormat = BE.numberFormat("$,.2f");
var dateFormat = BE.timeFormat("%b %Y");

dc.dateFormat = BE.timeFormat("%b %Y");

var salesChart = dc.barChart("#sales");
var salesStackChart = dc.barChart("#salesStack");
var yearChart = dc.pieChart("#years");
var typeChart = dc.rowChart("#types");
var langChart = dc.pieChart("#langs");
var cntrChart = dc.pieChart("#cntrs");
var dataTable = dc.dataTable("#clients");

function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

d3.json(url, function(error, data) {
	
	var cli = {};

	data.forEach(function(sale) {
		sale.created_at = Date.parse(sale.created_at.replace(' ', 'T'));
		sale.updated_at = Date.parse(sale.updated_at.replace(' ', 'T'));
		sale.due_date = Date.parse(sale.due_date.replace(' ', 'T'));
		sale.price_htva = +sale.price_htva;	// parseFloat(price_htva)
		sale.date_year = +sale.date_year;	// parseInt(date_year)
		sale.date_month = +sale.date_month;	// parseInt(date_year)
		var c=new Date(sale.created_at);
		sale.period = new Date(c.getFullYear(),c.getMonth(),c.getDate(),0,0,0,0);
		sale.period_month = new Date(c.getFullYear(),c.getMonth(),1,0,0,0,0);

		sale.document_type = typeof(docTypes[sale.document_type]) != 'undefined' ? docTypes[sale.document_type]['label'] : sale.document_type;

		if(sale.country)
			switch(sale.country.toLowerCase()) {
				case	'allemagne': 	sale.country = 'Allemagne'; break;
				case	'autriche': 	sale.country = 'Autriche'; break;
				case	'belgie':
				case	'belgique':
				case	'belgium': 		sale.country = 'Belgique'; break;
				case	'nederland':
				case	'holland':
				case	'hollande':
				case	'the netherlands':
				case	'pays-bas': 	sale.country = 'Pays-Bas'; break;
				case	'italia':
				case	'italie':c = 'Italie'; break;
				default:
					sale.country = toTitleCase(sale.country); break;
			}
		else
			sale.country = 'Indéfini';

		sale.language = sale.language == 'fr' ? 'Français' :
						sale.language == 'nl' ? 'Nederlands' :
						sale.language == 'en' ? 'English' :
						sale.language;
			
		cli[sale.client_id] = {
			name: sale.client_fn != '' ? sale.client_fn+' '+sale.client_ln :
					(sale.client_ln != '' ? sale.client_ln : sale.client_id),
			language: sale.language,
			country: sale.country
		}
		
		delete sale.client_fn,sale.client_ln,sale.client_an;

	})

	var ndx = crossfilter(data);
	var all = ndx.groupAll();

	var dateDim = ndx.dimension(function(d) { return d.period; });
	var totals = dateDim.group().reduceSum(function(d) {return d.price_htva;});

	var monthDim = ndx.dimension(function(d) { return d.period_month; });
	var monthTotals = monthDim.group().reduceSum(function(d) {return d.price_htva;});

	var minDate = dateDim.bottom(1)[0].period_month;
	var maxDate = dateDim.top(1)[0].period_month;
	//console.log(minDate, maxDate)
	dc.dataCount(".dc-data-count")
	  .dimension(ndx)
	  .group(all);

	var typeDim = ndx.dimension(function(d) { return d.document_type; });
	var type_total = typeDim.group().reduceSum(function(d) {return d.price_htva;});
	
	var yearDim  = ndx.dimension(function(d) {return +d.date_year;});
	var year_total = yearDim.group().reduceSum(function(d) {return d.price_htva;});
	
	var langDim  = ndx.dimension(function(d) {return d.language;});
	var lang_total = langDim.group().reduceSum(function(d) {return d.price_htva;});
	
	var cntrDim  = ndx.dimension(function(d) {return d.country;});
	var cntr_total = cntrDim.group().reduceSum(function(d) {return d.price_htva;});
	
	var cliDim  = ndx.dimension(function(d) {return d.client_id;});
	var cli_total = cliDim.group().reduceSum(function(d) {return d.price_htva;});
	
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
		
	yearChart
	    .width(150).height(150)
	    .dimension(yearDim)
	    .group(year_total)
	    .innerRadius(30)
		.turnOnControls(true);
		
	typeChart
	    .width(250).height(150)
	    .dimension(typeDim)
        .margins({left: 0, top: 0, right: 100, bottom: 0})
		.colors(docTypesColors)
	    .group(type_total)
		.turnOnControls(true);

	langChart
	    .width(150).height(150)
	    .dimension(langDim)
	    .group(lang_total)
	    .innerRadius(30)
		.turnOnControls(true)
		.minAngleForLabel(Math.PI / 40);

	cntrChart
	    .width(150).height(150)
	    .dimension(cntrDim)
	    .group(cntr_total)
	    .innerRadius(30)
		.turnOnControls(true)
		.minAngleForLabel(Math.PI / 40);

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
		.group(function(d) { return "Meilleurs clients satisfaisant les critères sélectionnés." })
		.size(16) // number of rows to return
		.columns([
		        function (d) {
		            return cli[d.key].name;
		        },
		        function (d) {
		            return cli[d.key].country;
		        },
		        function (d) {
		            return cli[d.key].language;
		        },
				function (d) {
		            return numberFormat(d.value);
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
$this->registerJs($this->blocks['JS_DC_SALES'], yii\web\View::POS_END);
