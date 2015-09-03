<?php

use yii\helpers\Url;
use miloschuman\highcharts\HighchartsAsset;

HighchartsAsset::register($this);

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
	        $('#lately').highcharts({

			        chart: {
			            type: 'column'
			        },

			        title: {
			            text: "<?= Yii::t('store', 'Monthly Report') ?>"
			        },

					credits: {
	            		enabled: false
	        		},

			        xAxis: {
			            categories: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc']
			        },

			        yAxis: {
			            allowDecimals: false,
			            min: 0,
			            title: {
			                text: '€'
			            }
			        },

			        tooltip: {
			            formatter: function () {
			                return '<b>' + this.x + '</b><br/>' +
			                    this.series.name + ': ' + this.y + '<br/>' +
			                    'Total: ' + this.point.stackTotal;
			            }
			        },

			        plotOptions: {
			            column: {
			                stacking: 'normal'
			            },
						series: {
			                cursor: 'pointer',
			                point: {
			                    events: {
			                        click: function() {
			                            window.open(this.options.url);
			                        }
			                    }
			                }
			            }
			        },

			        series: data
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
