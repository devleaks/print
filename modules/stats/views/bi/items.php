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
		        <a href="javascript:dc.filterAll(); dc.renderAll();">Annuler toutes les sélections</a>
		      </span>
		    </h1>
		</div><!--.span12-->
		
	</div><!--.row-->
	

	<div class="row">
		<div class="col-lg-4" id="years">			
			<h4>
				Année
		        <span>
		          <a class="reset"
		            href="javascript:yearChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
		<div class="col-lg-4" id="types">
			<h4>
				Types de documents
		        <span>
		          <a class="reset"
		            href="javascript:typeChart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
		        </span><br/>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h4>
		</div><!--.span3-->
		
		<div class="col-lg-4" id="cat1">
			<h4>
				Catégorie
		        <span>
		          <a class="reset"
		            href="javascript:cat1Chart.filterAll();dc.redrawAll();"
		            style="display: none;">- Annuler la sélection</a>
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
		            style="display: none;">- Annuler la sélection</a>
		        </span>
				<span class='reset' style='display: none;'>Sélection: <span class='filter'></span></span>			
			</h2>
		</div><!--.span12-->
		
	</div><!--.row-->
	

	<div class="row">
		
		<div class="col-lg-12">
			<div><h4>Articles</h4>			
			<table id="items" class="table">
				<thead>
				<tr class="header">
					<th>Article</th>
					<th>Catégorie</th>
					<th>Total</th>
					<th>Pourcentage</th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th><span id='localtotal'></span></th>
					<th>Grand Total: <span id="grandtotal"></span><br/><span id='localpercent'></span> %</th>
				</tr>
				</thead>
			</table>
			</div>
		</div><!--.span12-->
		
	</div><!--.row-->

</div><!--.container -->
<script type="text/javascript">
<?php $this->beginBlock('JS_DC_ITEMS'); ?>

var itmurl = "<?= Url::to(['/stats/bi-item'],['_format' => 'json']) ?>";
var url = "<?= Url::to(['/stats/bi-line'],['_format' => 'json']) ?>";

var BEn = d3.formatLocale({
    "decimal": ",",
    "thousands": ".",
    "grouping": [3],
    "currency": ["", " €"]
})
var BEd = d3.timeFormatLocale({
    "dateTime": "%a %b %e %X %Y",
    "date": "%d/%m/%Y",
    "time": "%H:%M:%S",
    "periods": ["AM", "PM"],
    "days": ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    "shortDays": ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    "months": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
    "shortMonths": ["Janv", "Févr", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]
})
var formatCurrency = BEn.format("$,.2f");
var percentFormat = BEn.format(",.1f");
var dateFormat = BEd.format("%b %Y");

dc.dateFormat = BEd.format("%b %Y");


var docTypes = {
	BID: 	{label: "Offre", 	color: "Plum"},
	BILL: 	{label: "Facture", 	color: "LimeGreen"},
	ORDER: 	{label: "Commande", color: "LightGreen"},
	TICKET: {label: "VC", 		color: "Aquamarine"},
	REFUND: {label: "Remb", 	color: "SandyBrown"},
	CREDIT: {label: "NC", 		color: "Coral"},
}

var detCategories = {
	chassis: 	{price: 'det_price_chassis',	name: 'det_chassis_id',	color: ''},
	chroma: 	{price: 'det_price_chroma',		name:  false,			color: '',		replace: 821},
	collage: 	{price: 'det_price_collage',	name: 'det_collage_id',	color: ''},
	corner: 	{price: 'det_price_corner',		name:  false,			color: '',		replace: 854},
	filmuv: 	{price: 'det_price_filmuv',		name:  false,			color: '',		replace: 845},
	frame: 		{price: 'det_price_frame',		name: 'det_frame_id',	color: ''},
	montage: 	{price: 'det_price_montage',	name:  false,			color: '',		replace: 884},
	protection: {price: 'det_price_protection',	name: 'det_protection_id',	color: ''},
	renfort: 	{price: 'det_price_renfort',	name: 'det_renfort_id',	color: ''},
	support: 	{price: 'det_price_support',	name: 'det_support_id',	color: ''},
	tirage: 	{price: 'det_price_tirage',		name: 'det_tirage_id',	color: ''},
};

var colors = [];
var labels = [];
for (var t in docTypes) {
    if (docTypes.hasOwnProperty(t)) {
		colors.push(docTypes[t]['color']);
		labels.push(docTypes[t]['label']);
    }
}
var docTypesColors = d3.scaleOrdinal().domain(labels).range(colors);

var salesStackChart = dc.barChart("#salesStack");
var yearChart = dc.pieChart("#years");
var typeChart = dc.rowChart("#types");
var cat1Chart = dc.rowChart("#cat1");
var dataTable = dc.dataTable("#items");
var localTotal   = dc.numberDisplay("#localtotal");	
var localPercent = dc.numberDisplay("#localpercent");	
var grand_total = null;	

function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt){txt = txt.trim(); return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function apply_rebate(sale) {
	/*
	$("#documentlinedetail-tirage_factor").change( function() {
		factor = parseFloat($("#documentlinedetail-tirage_factor").val());
		if(isNaN(factor)) {
			factor = 1.0;
			$("#documentlinedetail-tirage_factor").val(factor);
			$("#documentlinedetail-tirage_factor_virgule").val('1,0');
		}
		$('#documentlinedetail-price_tirage:enabled').val(arrondir_sup(item.prix_de_vente * factor));
		$('#documentlinedetail-price_tirage:enabled').trigger('change');
	});
	*/
	return (sale.extra_type == "REBATE_PERCENTAGE" || sale.extra_type == "SUPPLEMENT_PERCENTAGE");
}

var allItems = {};
var allItemsByName = {};

function detailDivers(i) {
	if(['Divers','Tâche'].indexOf(i.category) > 0 && i.yii_category && ['Sans','Divers'].indexOf(i.yii_category) < 0) {
	 	i.category += ' / '+toTitleCase(i.yii_category);
	}
}

function hasRebate(sale) {
    return (sale.extra_type == "REBATE_PERCENTAGE" || sale.extra_type == "SUPPLEMENT_PERCENTAGE");
}

function hasDetail(s) {
    return ["det_price_chroma",
        "det_chroma_id",
        "det_finish_id",
        "det_price_chassis",
        "det_chassis_id",
        "det_price_collage",
        "det_collage_id",
        "det_price_support",
        "det_support_id",
        "det_price_filmuv",
        "det_filmuv_bool",
        "det_price_frame",
        "det_frame_id",
        "det_price_protection",
        "det_protection_id",
        "det_price_montage",
        "det_montage_bool",
        "det_price_renfort",
        "det_renfort_id",
        "det_renfort_bool",
        "det_price_corner",
        "det_corner_bool",
        "det_price_tirage",
        "det_tirage_id",
        "det_tirage_factor"
    ].reduce(function(ret, i) {
        return ret || (s[i] !== null);
    });
}


d3.json(itmurl).then( (data) => {
	/**{
		"id":821,
		"libelle_court":"ChromaLuxe",
		"categorie":"ChromaLuxe",
		"yii_category":"ChromaLuxe"
	}**/
	data.forEach(function(i) {
		i.category = i.categorie ? toTitleCase(i.categorie) : "Sans";
		
		detailDivers(i);

		allItems[i.id] = i;		
		allItemsByName[i.libelle_court] = i;
	});
	//console.log('items loaded', allItems);
}).then(() => {
	d3.json(url).then( (data) => {
    	/**{
    		"document_type":"TICKET",
    		"document_status":"CLOSED",
    		"document_name":"2015-A-1062",
    		"created_at":"2015-01-30T11:39:46Z",
    		"work_width":50,
    		"work_height":75,
    		"unit_price":"145.00",
    		"quantity":1,
    		"extra_type":"REBATE_PERCENTAGE",
    		"extra_amount":"10.00",
    		"extra_htva":"-14.50",
    		"price_htva":"145.00",
    		"total_htva":"130.50",
    		"item_name":"ChromaLuxe",
    		"categorie":"ChromaLuxe",
    		"yii_category":"ChromaLuxe",
    		"comptabilite":"700200",

    		"det_price_chroma":"131.00",
    		"det_chroma_id":880,
    		"det_finish_id":null,

    		"det_price_chassis":null,
    		"det_chassis_id":null,

    		"det_price_collage":null,
    		"det_collage_id":null,

    		"det_price_support":null,
    		"det_support_id":null,

    		"det_price_filmuv":null,
    		"det_filmuv_bool":0,

    		"det_price_frame":null,
    		"det_frame_id":null,

    		"det_price_protection":null,
    		"det_protection_id":null,

    		"det_price_montage":null,
    		"det_montage_bool":0,

    		"det_price_renfort":"14.00",
    		"det_renfort_id":847,
    		"det_renfort_bool":1,

    		"det_price_corner":null,
    		"det_corner_bool":0,

    		"det_price_tirage":null,
    		"det_tirage_id":null
    		"det_tirage_factor":null,

    	}**/

        var detail_data = [];
        var splitted = 0;

        data.forEach(function(sale) {
            sale.created_at = Date.parse(sale.created_at.replace(' ', 'T'));
            sale.total_htva = +sale.total_htva;
            sale.quantity = sale.quantity ? (+sale.quantity) : 1;
            sale.extra_amount = +sale.extra_amount;

            sale.document_type = typeof(docTypes[sale.document_type]) != 'undefined' ? docTypes[sale.document_type]['label'] : sale.document_type;

            sale.category = sale.categorie ? toTitleCase(sale.categorie) : "Sans";

            detailDivers(sale);

            var c = new Date(sale.created_at);
            sale.period = new Date(c.getFullYear(), c.getMonth(), c.getDate(), 0, 0, 0, 0);
            sale.period_month = new Date(c.getFullYear(), c.getMonth(), 1, 0, 0, 0, 0);
            sale.year = c.getFullYear();


            if (sale.total_htva > 0 && hasDetail(sale)) { // we split the line
                splitted++;
                var percents = hasRebate(sale) ? ((sale.extra_type == "REBATE_PERCENTAGE") ? -sale.extra_amount / 100 : +sale.extra_amount / 100) : 0;
                for (var detail in detCategories) {
                    if (detCategories.hasOwnProperty(detail)) {
                        var price = detCategories[detail]['price'];
                        if (sale[price] != null && sale[price] != '' && (+sale[price] > 0)) {
                            sale[price] = +sale[price];
                            sale[price] += percents * sale[price];
                            var newline = JSON.parse(JSON.stringify(sale)); // duplicate the line
                            newline.period_month = Date.parse(newline.period_month);
                            //move price to new line
                            newline.total_htva = sale.quantity * sale[price];
                            sale.total_htva -= newline.total_htva;
                            //adjust item and categorie of new line
                            var item = detCategories[detail]['name'] ? allItems[sale[detCategories[detail]['name']]] : allItems[detCategories[detail]['replace']];
                            ['categorie', 'yii_category'].forEach(function(f) {
                                newline[f] = toTitleCase(item[f]);
                            })
                            newline.category = newline['categorie'];
                            detailDivers(newline);

                            detail_data.push(newline);
                        }
                    }
                }
            }
        });
        // console.log('Line splitted, added, before, after', splitted, detail_data.length, data.length, data.length + detail_data.length);
        data = data.concat(detail_data);

        var ndx = crossfilter(data);
        var all = ndx.groupAll();

        var dateDim = ndx.dimension(function(d) { return d.period; });
        //var totals = dateDim.group().reduceSum(function(d) {return d.total_htva;});

        var monthDim = ndx.dimension(function(d) { return d.period_month; });
        var monthTotals = monthDim.group().reduceSum(function(d) { return d.total_htva; });

        var minDate = dateDim.bottom(1)[0].period_month;
        var maxDate = dateDim.top(1)[0].period_month;
        //console.log(minDate, maxDate)
        dc.dataCount(".dc-data-count")
            .crossfilter(ndx)
            .groupAll(all);

        var typeDim = ndx.dimension(function(d) { return d.document_type; });
        var type_total = typeDim.group().reduceSum(function(d) { return d.total_htva; });

        var yearDim = ndx.dimension(function(d) { return d.year; });
        var year_total = yearDim.group().reduceSum(function(d) { return d.total_htva; });

        var cat1Dim = ndx.dimension(function(d) { return d.category });
        var cat1_total = cat1Dim.group().reduceSum(function(d) { return d.total_htva; });

        var itemDim = ndx.dimension(function(d) { return d.item_name; });
        var item_total = itemDim.group().reduceSum(function(d) { return d.total_htva; });

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
                for (var t in docTypes) {
                    if (docTypes.hasOwnProperty(t)) {
                        e[docTypes[t]['label']] = 0;
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
            .width(1000).height(250)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .margins({ left: 50, top: 10, right: 50, bottom: 20 })
            .xUnits(d3.timeMonths)
            .dimension(monthDim)
            .group(typeSumGroup, "Commande")
            .colors(docTypesColors)
            .elasticY(true)
            .renderHorizontalGridLines(true)
            .valueAccessor(function(d) {
                return d.value["Commande"];
            });
        //console.log("stacking "+docTypes[ssinit]);

        for (var t in docTypes) {
            if (t != ssinit && docTypes.hasOwnProperty(t)) {
                //console.log("stacking "+docTypes[t]);
                salesStackChart
                    .stack(typeSumGroup, docTypes[t]['label'], sel_stack(docTypes[t]['label']));
            }
        }

        localTotal.group(
            itemDim.groupAll().reduceSum(function(d) { return +d.total_htva; })
        ).valueAccessor(function(d) {
            //console.log(d);
            return d;
        }).formatNumber(formatCurrency);

        if (grand_total === null) {
            grand_total = localTotal.value();
            d3.selectAll('#grandtotal').text(formatCurrency(grand_total));
        }

        localPercent.group(
            itemDim.groupAll().reduceSum(function(d) { return +d.total_htva; })
        ).valueAccessor(function(d) {
            //console.log(d);
            return 100 * d / grand_total;
        }).formatNumber(percentFormat);


        // salesStackChart.legend(dc.legend());

        // https://stackoverflow.com/questions/39811210/dc-charts-change-legend-order
        const super_legendables = salesStackChart.legendables;
        salesStackChart.legendables = function() {
            const items = super_legendables.call(this);
            return items.reverse();
        }

        yearChart
            .width(150).height(150)
            .dimension(yearDim)
            .group(year_total)
            .innerRadius(30)
            .turnOnControls(true)
            .title(function(d) { return d.key + ": " + formatCurrency(d.value); });

        typeChart
            .width(250).height(150)
            .margins({ left: 0, top: 0, right: 100, bottom: 0 })
            .dimension(typeDim)
            .colors(docTypesColors)
            .group(type_total)
            .turnOnControls(true)
            .title(function(d) { return d.key + ": " + formatCurrency(d.value); });

        cat1Chart
            .width(350).height(350)
            .margins({ left: 0, top: 0, right: 100, bottom: 0 })
            .dimension(cat1Dim)
            .group(cat1_total)
            .turnOnControls(true)
            .title(function(d) { return d.key + ": " + formatCurrency(d.value); });

        dataTable
            .dimension(item_total)
            .section(function(d) { return "Articles satisfaisant les critères sélectionnés, classés par chiffre d'affaire." })
            .size(10) // number of rows to return
            .columns([
                function(d) {
                    return d.key;
                },
                function(d) {
                    return allItemsByName[d.key] ? allItemsByName[d.key].category : 'Manquante pour ' + d.key;
                },
                function(d) {
                    return formatCurrency(d.value);
                },
                function(d) {
                    return percentFormat(100 * d.value / localTotal.value());
                }

            ])
            .sortBy(function(d) { return d.value; })
            .order(d3.descending);

        dc.renderAll();
        dc.filterAll();
    });
});		
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_DC_ITEMS'], yii\web\View::POS_END);
