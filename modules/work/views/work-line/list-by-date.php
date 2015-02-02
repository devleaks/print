<?php

use app\models\Document;
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

$this->title = Yii::t('store', 'Tasks '.Document::getDateWords($day));
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

            //'id',
			[
	            'label' => Yii::t('store', 'Order'),
				'attribute' => 'work.document.name',
				'noWrap' => true,
			],
            [
	            'label' => Yii::t('store', 'Client'),
				'attribute' => 'work.document.client.nom',
            ],
			[
				'attribute' => 'work.document.due_date',
				'format' => 'date'
			],
	        [
				'attribute' => 'item_name',
	            'label' => Yii::t('store', 'Item'),
	            'value' => function ($model, $key, $index, $widget) {
	                return $model->item->libelle_court;
	            },
				'hAlign' => GridView::ALIGN_CENTER,
	            'format' => 'raw',
	        ],
	        [
				'attribute' => 'task_name',
	            'label' => Yii::t('store', 'Task'),
				'filter' => ArrayHelper::map(Task::find()->asArray()->all(), 'name', 'name'),
	            'value' => function ($model, $key, $index, $widget) {
					$wi = $model->getTask()->one();
	                return $wi ? $wi->name : '';
	            },
	            'format' => 'raw',
	        ],
            [
				'attribute' => 'status',
                'label'=>Yii::t('store','Status'),
	            'filter' => Work::getStatuses(),
	            'value'=> function ($model, $key, $index, $widget) {
					return $model->getStatusLabel();
				},
				'hAlign' => GridView::ALIGN_CENTER,
            	'format' => 'raw',
            ],
			[
	            'label' => Yii::t('store', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
	        [
				'attribute' => 'updated_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'label' => Yii::t('store', 'Updated By'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getUpdatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
	        [
				'attribute' => 'created_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'label' => Yii::t('store', 'Created By'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->getCreatedBy()->one();
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
			],
        ],
    ]); ?>

</div>
