<?php

use app\models\Client;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Habitudes des meilleurs clients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Statistics'), 'url' => ['/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'client_id',
			    'value' => function ($model, $key, $index, $widget) {
					return Client::findOne($model['client_id'])->nom;
				},
			],
            'total_amount:currency',
            'total_count',
            'avg_amount_per_day:currency',
			[
				'attribute' => 'avg_day_between_order',
			    'value' => function ($model, $key, $index, $widget) {
					return round($model['avg_day_between_order'], 1);
				},
			],
        ],
    ]) ?>

</div>
