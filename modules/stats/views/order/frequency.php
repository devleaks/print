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
<div class="parameter-index container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'client_id',
				'label' => Yii::t('store','Client'),
			    'value' => function ($model, $key, $index, $widget) {
					return Client::findOne($model['client_id'])->nom;
				},
			],
			[
				'attribute' => 'total_amount',
				'format' => 'currency',
				'label' => Yii::t('store','Montant commandes'),
				'hAlign' => GridView::ALIGN_RIGHT,
			],
			[
				'attribute' => 'total_count',
				'format' => 'integer',
				'label' => Yii::t('store','Nb. commandes'),
				'hAlign' => GridView::ALIGN_CENTER,
			],
			[
				'attribute' => 'avg_amount_per_day',
				'format' => 'currency',
				'label' => Yii::t('store','Montant moyen par jour'),
				'hAlign' => GridView::ALIGN_RIGHT,
			],
			[
				'attribute' => 'avg_day_between_order',
				'label' => Yii::t('store','Nb. jours entre commandes'),
			    'value' => function ($model, $key, $index, $widget) {
					return round($model['avg_day_between_order'], 1);
				},
				'hAlign' => GridView::ALIGN_RIGHT,
			],
        ],
    ]) ?>

</div>
