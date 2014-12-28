<?php

use app\models\Work;
use kartik\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\Url;

Icon::map($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Works');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        [
				'attribute' => 'order_name',
				'label' => Yii::t('store', 'Order'),
	            'value' => function ($model, $key, $index, $widget) {
                    return in_array(Yii::$app->user->identity->role, ['manager', 'admin']) ? 
							Html::a($model->document->name, Url::to(['/order/document/view', 'id' => $model->document_id]))
							 : $model->document->name;
	            },
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'client_name',
				'label' => Yii::t('store', 'Client'),
	            'value' => function ($model, $key, $index, $widget) {
                    return $model->document->client->nom;
	            },
	            'format' => 'raw',
	        ],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
	        [
	            'attribute' => 'status',
	            'filter' => Work::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
	        [
	            'label' => Yii::t('store', 'Tasks'),
	            'value' => function ($model, $key, $index, $widget) {
	                    return $model->getTaskIcons(true, true, true);
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
            [	'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
	            'buttons' => [
	                'view' => function ($url, $model) {
						$url = Url::to(['work/view', 'id' => $model->id, 'sort' => 'position']);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
				],
			],
        ],
    ]); ?>

</div>
