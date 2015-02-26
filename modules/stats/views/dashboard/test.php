<?php
$s = '{"chart":{"plotBackgroundColor":null,"plotBorderWidth":0,"plotShadow":false},"title":{"text":"Browser<br>shares","align":"center","verticalAlign":"middle","y":50},"tooltip":{"pointFormat":"{series.name}: <b>{point.percentage:.1f}%</b>"},"plotOptions":{"pie":{"dataLabels":{"enabled":true,"distance":-50,"style":{"fontWeight":"bold","color":"white","textShadow":"0px 1px 2px black"}},"startAngle":-90,"endAngle":90,"center":["50%","75%"]}},"series":[{"type":"pie","name":"Browser share","innerSize":"50%","data":[["Firefox",45],["IE",26.8],["Chrome",12.8],["Safari",8.5],["Opera",6.2],{"name":"Others","y":0.7,"dataLabels":{"enabled":false}}]}]}';
$t = json_decode($s);
echo '<pre>'.print_r($t, true).'</pre>';
?>
<div>
	<form>
		<textarea rows="40" cols="120" id="src">{
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
    }</textarea>
		<br/>
		<input id="doit" type="button" value="Convert">
	</form>
</div>
<div id="dest" style="border: 1px solid grey;">
	toto
</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_TEST'); ?>
$("#doit").click(function() {
	//console.log($("#src").val());
	t = eval("o="+$("#src").val());
	console.log(JSON.stringify(t));
	$("dest").text("Toto" + JSON.stringify(t));
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_TEST'], yii\web\View::POS_END);