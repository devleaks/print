<?php

use app\models\ItemTask;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use kartik\icons\Icon;

Icon::map($this);

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = Yii::t('store', "Items' Associated Tasks");
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

<?php foreach($query->each() as $item): ?>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider(['query' => $item->getItemTasks()]),
		'panelHeadingTemplate' => '{heading}',
		'panel' => [
	        'heading'=> '<h3 class="panel-title">'.$item->libelle_long.'</h3>',
			'before'=> false,
			'after' => false,
			'footer'=> false,
	    ],
        'columns' => [
            // 'id',
            'task.name',
	        [
	            'label' => Yii::t('store', 'Icon'),
	            'value' => function ($model, $key, $index, $widget) {
							return Icon::show($model->task->icon);
	            		},
				'format' => 'raw',
				'hAlign' => GridView::ALIGN_CENTER,
	        ],
			'position',
            'note',
            // 'first_run',
            // 'next_run',
            // 'unit_cost',
//	        [
//	            'label' => Yii::t('store', 'Status'),
//	            'value' => function ($model, $key, $index, $widget) {
//							return Yii::t('store', $model->status);
//	            		},
//	        ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn',
			 'controller' => 'item-task'],
		]
	]) ?>
<?php endforeach; ?>
	
</div>
