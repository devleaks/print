<?php

use yii\helpers\Url;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['modules/drilldown']);

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
		if(data.length > 0) {
	        $("#workline<?= $date ?>").highcharts({
		        chart: {
		            plotBackgroundColor: null,
		            plotBorderWidth: 0,
		            plotShadow: false
		        },
				title: {
					text: "<?= Yii::t('store', $title) ?>"
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
		            name: 'To Do',
		            innerSize: '50%',
		            data: data
		        }]
		    });
		} else {
			$("#workline<?= $date ?>").html("<?= Yii::t('store', $title).': '.Yii::t('store', 'No data.') ?>");
		}
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_WORKLINE_CHART'.$date], yii\web\View::POS_END);
