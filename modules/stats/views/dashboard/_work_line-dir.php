<?php

use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="dashboard-work-line">

	<?= Highcharts::widget([
		'options' => [
		    'title' => ['text' => Yii::t('store', $title)],
			'plotOptions'=> [
	            'pie'=> [
	                'dataLabels'=> [
	                    'enabled'=> true,
	                    'distance'=> -50,
	                    'style'=> [
	                        'fontWeight'=> 'bold',
	                        'color'=> 'white',
	                        'textShadow'=> '0px 1px 2px black'
	                    ]
	                ],
                'startAngle'=> -90,
                'endAngle'=> 90,
                'center'=> ['50%', '75%']
            	]
        	],
			'series' => [ [
				'type' => 'pie',
				'innerSize' => '50%',
				'data' => [
					['DONE', 24],
					['TODO', 12]
				]
			] ]
		]
	]);?>

</div>