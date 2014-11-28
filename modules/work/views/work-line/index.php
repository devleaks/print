<?php

use app\models\Task;
use app\models\User;
use app\models\Work;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Tasks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-line-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        [
	            'label' => Yii::t('store', 'Commande'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->work->order->name;
	            },
	            'format' => 'raw',
	        ],
	        [
	            'label' => Yii::t('store', 'Task'),
				'attribute' => 'task_id',
				'filter' => ArrayHelper::map(Task::find()->asArray()->all(), 'id', 'name'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->task->name;
	            },
	            'format' => 'raw',
	        ],
	        [
	            'label' => Yii::t('store', 'Status'),
	            'attribute' => 'status',
	            'filter' => Work::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getStatusLabel();
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	
	        ],
	        [
	            'label' => Yii::t('store', 'Due Date'),
				'attribute' => 'due_date',
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->due_date;
	            },
	            'format' => 'date',
	        ],
	            'updated_at',
	        [
	            'label' => Yii::t('store', 'Updated By'),
				'attribute' => 'updated_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getUpdatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],
	            'created_at',
//	        [
//	            'label' => Yii::t('store', 'Created By'),
//	            'value' => function ($model, $key, $index, $widget) {
//					$user = $model->getCreatedBy()->one();
//	                return $user ? $user->username : '?';
//	            },
//	            'format' => 'raw',
//	        ],
	        [
	            'class' => 'kartik\grid\ActionColumn',
	            'template' => '{detail} {take} {done} {undo}',
				'noWrap' => true,
	            'buttons' => [
	                'detail' => function ($url, $model) {
						$url = Url::to(['work-line/detail', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'class' => 'btn btn-xs btn-info',
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
	                'take' => function ($url, $model) {
						$url = Url::to(['work-line/take', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-inbox"></i>', $url, [
	                        'class' => 'btn btn-xs btn-primary',
	                        'title' => Yii::t('store', 'Take'),
	                    ]);
	                },
	                'done' => function ($url, $model) {
						$url = Url::to(['work-line/done', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $url, [
	                        'class' => 'btn btn-xs btn-success',
	                        'title' => Yii::t('store', 'Done'),
	                        'data-confirm' => Yii::t('store', 'Did you terminate this task?'),
	                    ]);
	                },
	                'undo' => function ($url, $model) {
						$url = Url::to(['work-line/undo', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-remove"></i>', $url, [
	                        'class' => 'btn btn-xs btn-danger',
	                        'title' => Yii::t('store', 'Redo'),
	                        'data-confirm' => Yii::t('store', 'Do you want to UNDO/REDO this task?'),
	                    ]);
	                },
	            ]
	        ],
		]
    ]); ?>

</div>
