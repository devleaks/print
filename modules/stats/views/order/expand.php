<?php

use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;

HighchartsAsset::register($this)->withScripts(['highstock']);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$v = '{"chart":{"type":"bar"},"title":{"text":"Historic World Population by Region"},"subtitle":{"text":"Source: Wikipedia.org"},"xAxis":{"categories":["Africa","America","Asia","Europe","Oceania"],"title":{"text":null}},"yAxis":{"min":0,"title":{"text":"Population (millions)","align":"high"},"labels":{"overflow":"justify"}},"tooltip":{"valueSuffix":" millions"},"plotOptions":{"bar":{"dataLabels":{"enabled":true}}},"legend":{"layout":"vertical","align":"right","verticalAlign":"top","x":-40,"y":100,"floating":true,"borderWidth":1,"backgroundColor":"#FFFFFF","shadow":true},"credits":{"enabled":false},"series":[{"name":"Year 1800","data":[107,31,635,203,2]},{"name":"Year 1900","data":[133,156,947,408,6]},{"name":"Year 2008","data":[973,914,4054,732,34]}]}';
$data = json_decode($v);

echo '<pre>'.var_Export($data, true).'</pre>';

?>
<pre>
<div id="temp-here"></div>
</pre>
<script type="text/javascript">
<?php
$this->beginBlock('JS_TEMP') ?>
obj = {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Historic World Population by Region'
        },
        subtitle: {
            text: 'Source: Wikipedia.org'
        },
        xAxis: {
            categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Population (millions)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' millions'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Year 1800',
            data: [107, 31, 635, 203, 2]
        }, {
            name: 'Year 1900',
            data: [133, 156, 947, 408, 6]
        }, {
            name: 'Year 2008',
            data: [973, 914, 4054, 732, 34]
        }]
    };
str = JSON.stringify(obj);
console.log(str);
$('#temp-here').html(str);
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_TEMP'], yii\web\View::POS_READY);
