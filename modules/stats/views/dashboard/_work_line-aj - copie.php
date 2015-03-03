<?php

use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div id="workline<?= $date ?>" class="dashboard-work-line">
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_WORKLINE_CHART'.$date); ?>
$(function() {
    $.getJSON("<?= Url::to(['work-lines', 'id' => $date]) ?>", function(data) {
        $("#workline<?= $date ?>").highcharts({
	        chart: {
	            plotBackgroundColor: null,
	            plotBorderWidth: 0,
	            plotShadow: false
	        },
	        title: {
	            text: 'Browser<br>shares',
	            align: 'center',
	            verticalAlign: 'middle',
	            y: 50
	        },
	        tooltip: {
	            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	        },
			credits: {
           		enabled: false
       		},
	        plotOptions: {
	            pie: {
	                dataLabels: {
	                    enabled: true,
	                    distance: -50,
	                    style: {
	                        fontWeight: 'bold',
	                        color: 'white',
	                        textShadow: '0px 1px 2px black'
	                    }
	                },
	                startAngle: -90,
	                endAngle: 90,
	                center: ['50%', '75%']
	            }
	        },
	        series: [{
	            type: 'pie',
	            name: 'Browser share',
	            innerSize: '50%',
	            data: [
	                ['Firefox',   45.0],
	                ['IE',       26.8],
	                ['Chrome', 12.8],
	                ['Safari',    8.5],
	                ['Opera',     6.2],
	                {
	                    name: 'Others',
	                    y: 0.7,
	                    dataLabels: {
	                        enabled: false
	                    }
	                }
	            ]
	        }]
	    });
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_WORKLINE_CHART'.$date], yii\web\View::POS_END);
