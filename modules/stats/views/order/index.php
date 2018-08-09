<?php

use app\models\Client;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Facturé par clients');
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
			    'class'=>'kartik\grid\ExpandRowColumn',
			    'width'=>'50px',
			    'value'=>function ($model, $key, $index, $column) {
			        return GridView::ROW_COLLAPSED;
			    },
			    'detail'=>function ($model, $key, $index, $column) {
			        return Yii::$app->controller->renderPartial('_client_per_year', ['model'=>$model]);
			    },
			    'headerOptions'=>['class'=>'kartik-sheet-style'],
			    'expandOneOnly'=>true
			],

			[
				'attribute' => 'client_id',
				'label' => Yii::t('store','Client'),
			    'value' => function ($model, $key, $index, $widget) {
					return Client::findOne($model['client_id'])->nom;
				},
			],
			[
				'attribute' => 'tot_price',
				'format' => 'currency',
				'label' => Yii::t('store','Facturé'),
				'hAlign' => GridView::ALIGN_RIGHT,
			],
			[
				'attribute' => 'tot_count',
				'format' => 'integer',
				'label' => Yii::t('store','Nb. commandes'),
				'hAlign' => GridView::ALIGN_CENTER,
			],
        ],
    ]) ?>

</div>
