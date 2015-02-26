<?php

use app\models\Parameter;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title).' '.Html::a(Yii::t('store', 'Create Event'), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
			[
				'attribute' => 'event_type',
				'filter' => Parameter::getSelectList('event_type', 'value_text'),
			    'value' => function ($model, $key, $index, $widget) {
					return Parameter::getTextValue('event_type', $model->event_type);
				},
			],
			[
				'attribute' => 'date_from',
	            'label' => Yii::t('store', 'Date From'),
				'format' => 'date',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date_from);
				}
			],
			[
				'attribute' => 'date_to',
	            'label' => Yii::t('store', 'Date To'),
				'format' => 'date',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->date_to);
				}
			],
	        [
	            'attribute' => 'status',
	            'filter' => ['ACTIVE' => Yii::t('store', 'ACTIVE'), 'INACTIVE' => Yii::t('store', 'INACTIVE')],
				'hAlign' => GridView::ALIGN_CENTER,
				'value' => function ($model, $key, $index, $widget) {
					return Yii::t('store', $model->status);
				}
	        ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
