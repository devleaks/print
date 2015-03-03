<?php

use app\models\Item;
use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\helpers\VarDumper;

HighchartsAsset::register($this)->withScripts(['modules/drilldown']);

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$catdata = [];
$subdata = [];
foreach($dataProvider->query->each() as $m) {
	$cat = $m['category'] == ''? 'Sans' :$m['category'];
	
	if(!isset($catdata[$cat])) {
		$catdata[$cat] = 0;
		$subdata[$cat] = [];
	}
	$catdata[$cat] += intval($m['tot_count']);
	$subdata[$cat][] = [$m['name'] == '' ? 'Sans' : $m['name'], intval($m['tot_count'])];
}

$data = [];
foreach($catdata as $k => $d)
	$data[] = [
		'name' => $k,
		'y' => $d,
		'drilldown' => $k
	];

$drilldowns = [];
foreach($subdata as $k => $d)
	$drilldowns[] = [
		'id' => $k,
		'data' => $d
	];

$this->title = Yii::t('store', 'Item Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo '';/* GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_id',
            'total',
        ],
    ]); 	<?= VarDumper::dump($data) ?> <?= '<pre>'.print_r($data, true).'</pre>' ?> */ ?>

	<?= Highcharts::widget([
		'options' => [
			'chart' => [
				'type' => 'pie'
			],
		    'title' => [
				'text' => Yii::t('store', 'Item Categories')
			],
			'credits' => [
	            'enabled' => false
	        ],
		    'xAxis' => [
		        'type' => 'category'
			],
			'series' => [ [
				'name' => 'Category',
				'colorByPoint' => true,
				'data' => $data
				]
			],
			'drilldown' => [
				'series' => $drilldowns,
			]
		]
	]);?>

</div>
<script type="text/javascript">
<?php $this->beginBlock('JS_RADIALIZE_COLORS'); ?>
// Radialize the colors
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
        ]
    };
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_RADIALIZE_COLORS'], yii\web\View::POS_END);
