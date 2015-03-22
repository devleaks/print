<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PriceListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Composite Price Lists');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-list-index">

    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Price List'), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'note',
            //'sizes',
            'status',
            // 'created_at',
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],

            [
				'class' => 'yii\grid\ActionColumn',
	            'template' => '{table} {view} {update} {delete}',
	            'buttons' => [
	                'table' => function ($url, $model) {
						$url = Url::to(['price-list/table', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-th"></i>', $url, [
	                        'title' => Yii::t('store', 'Table'),
	                    ]);
	                },
				]
			],
        ],
    ]); ?>

</div>
