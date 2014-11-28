<?php

use app\models\ItemTask;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$dataProvider = new ActiveDataProvider([
	'query' => ItemTask::find()->where(['item_id' => $model->id])->orderBy('position'),
]);

?>
<div class="task-view">

	<p></p>
    <h2><?= Yii::t('store', 'Associated Tasks') ?></h2>
	<p></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'position',
            // 'id',
            // 'created_at',
            // 'updated_at',
            'task.name',
            'task.first_run',
            'task.next_run',
            'task.unit_cost',
            'task.status',

            ['class' => 'yii\grid\ActionColumn',
             'controller' => 'item-task',
			 'template' => '{update} {delete}'],
        ],
    ]); ?>
	<p></p>

	<?php
		$iw = new ItemTask();
		$iw->item_id = $model->id;
		echo $this->render('_add', ['model'=>$iw]);
	?>

</div>
