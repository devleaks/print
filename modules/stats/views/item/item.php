<?php

use app\models\Item;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$data = [];
foreach($dataProvider->query->each() as $m) {
	if($m['name'] == 'ChromaLuxe') {
		$data[] = [
			'name' => $m['name'],
			'y' => intval($m['tot_count']),
			'sliced' => true,
			'selected' => true
		];
	} else
		$data[] = [$m['name'], intval($m['tot_count'])];
}

$data2 = [];
foreach($dataProvider2->query->each() as $m) {
	if($m['name'] == 'ChromaLuxe') {
		$data2[] = [
			'name' => $m['name'],
			'y' => intval($m['tot_count']),
			'sliced' => true,
			'selected' => true
		];
	} else
		$data2[] = [$m['name'], intval($m['tot_count'])];
}

$this->title = Yii::t('store', 'Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo '';/* GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_id',
            'total',
        ],
    ]); 	<?= VarDumper::dump($data) ?> */ ?>




	<?= Highcharts::widget([
		'options' => [
		    'title' => ['text' => Yii::t('store', 'Items (Quantity)')],
			'tooltip' => [
				'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
			],
			'credits' => [
	            'enabled' => false
	        ],
			'series' => [ [
				'type' => 'pie',
				'name' => 'Item',
				'data' => $data
				]
			]
		]
	]);?>

	<?= Highcharts::widget([
		'options' => [
		    'title' => ['text' => Yii::t('store', 'Items (Money)')],
			'tooltip' => [
				'pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'
			],
			'credits' => [
	            'enabled' => false
	        ],
			'series' => [ [
				'type' => 'pie',
				'name' => 'Item',
				'data' => $data2
				]
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



