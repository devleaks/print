<?php

use yii\helpers\Url;
use miloschuman\highcharts\HighchartsAsset;
HighchartsAsset::register($this)->withScripts(['modules/drilldown']);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div id="today-doc" class="dashboard-work-line">
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_WORKLINE_CHART_TODAY_DOC'); ?>
$(function() {
    $.getJSON("<?= Url::to(['documents']) ?>", function(data) {
		console.log(data);
		if(data.length > 0) {
	        $("#today-doc").highcharts({
		        chart: {
		            plotBackgroundColor: null,
		            plotBorderWidth: 0,
		            plotShadow: false
		        },
				title: {
					text: "<?= Yii::t('store', $title) ?>"
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
			$("#today-doc").html("<?= Yii::t('store', 'No data.') ?>");
		}
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_WORKLINE_CHART_TODAY_DOC'], yii\web\View::POS_END);
