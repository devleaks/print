<?php

use app\models\ItemTask;
use app\models\Task;
use app\models\User;
use app\models\Work;
use kartik\grid\GridView;
use kartik\icons\Icon;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Icon::map($this);

?>
<div class="work-line-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//			['class' => 'kartik\grid\SerialColumn'],
	        [
				'attribute' => 'position',
				'filter' => false,
			],
	        [
				'attribute' => 'task_name',
	            'label' => Yii::t('store', 'Task'),
				'filter' => ArrayHelper::map(Task::find()->asArray()->all(), 'name', 'name'),
	            'value' => function ($model, $key, $index, $widget) {
					$wi = $model->getTask()->one();
	                return $wi ? Icon::show($wi->icon) . ' ' . $wi->name : '';
	            },
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'item_name',
	            'label' => Yii::t('store', 'Item'),
	            'value' => function ($model, $key, $index, $widget) {
					return $model->item->libelle_long;
	            },
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'status',
	            'label' => Yii::t('store', 'Status'),
	            'filter' => Work::getStatuses(),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->getStatusLabel();
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			[
				'attribute' => 'due_date',
				'format' => 'date',
			],
			[
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				},					
			],
	        [
				'attribute' => 'updated_by',
	            'label' => Yii::t('store', 'Updated By'),
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getUpdatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
//			[
//				'attribute' => 'created_at',
//				'format' => 'datetime',
//			],
//	        [
//				'attribute' => 'created_by',
//	            'label' => Yii::t('store', 'Created By'),
//				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
//	            'value' => function ($model, $key, $index, $widget) {
//					$user = $model->getCreatedBy()->one();
//	                return $user ? $user->username : '?';
//	            },
//	            'format' => 'raw',
//	        ],
	        [
	            'class' => 'kartik\grid\ActionColumn',
	            'template' => '{detail} {label} {take} {done} {undo}',
	            'buttons' => [
	                'detail' => function ($url, $model) {
						$url = Url::to(['work-line/detail', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
	                        'class' => 'btn btn-xs btn-info',
	                        'title' => Yii::t('store', 'View'),
	                    ]);
	                },
	                'label' => function ($url, $model) {
						$url = Url::to(['/order/document-line/label', 'id' => $model->document_line_id]);
	                    return Html::a('<i class="glyphicon glyphicon-tag"></i>', $url, [
	                        'class' => 'btn btn-xs btn-info',
	                        'title' => Yii::t('store', 'Labels'),
							'target' => '_blank',
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
	            ],
				'noWrap' => true,
	        ],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
