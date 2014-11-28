<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;

Icon::map($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->title = Yii::t('store', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?>
        <?= Html::a(Yii::t('store', 'Create Task'), ['create'], ['class' => 'btn btn-success']) ?>
	</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
	        [
	            'label' => Yii::t('store', 'Icon'),
	            'value' => function ($model, $key, $index, $widget) {
							return Icon::show($model->icon);
	            		},
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
            'note',
            // 'first_run',
            // 'next_run',
            // 'unit_cost',
	        [
	            'label' => Yii::t('store', 'Status'),
	            'value' => function ($model, $key, $index, $widget) {
							return Yii::t('store', $model->status);
	            		},
	        ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
