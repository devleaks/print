<?php

use app\models\Bid;
use app\models\Bill;
use app\models\Document;
use app\models\User;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="website-order-line-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

	        [
				'attribute' => 'filename',
			],
	        [
				'attribute' => 'format',
			],
	        [
				'attribute' => 'finish',
			],
	        [
				'attribute' => 'profile',
			],
	        [
				'attribute' => 'quantity',
			],
	        [
				'attribute' => 'comment',
			],
	        [
	            'attribute' => 'status',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
            [	// freely let update or delete if accessed throught this screen.
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{delete}',
				'noWrap' => true,
			],

        ],
    ]); ?>

</div>