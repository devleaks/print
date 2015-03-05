<?php
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;
Icon::map($this);

$query = WorkLine::find()
			->select('task_id, count(task_id) as total')
			->where(['!=', 'status', Work::STATUS_DONE])
			->groupBy('task_id');

foreach($query->each() as $task_id) {
	$task = Task::findOne($task_id->task_id);
	echo Html::a(Icon::show($task->icon) . ' ' .$task->name.'<span class="badge">'.$task_id->total.'</span>',
		Url::to(['/work/work-line/list-task', 'id' => $task_id->task_id, 'sort'=> '-due_date']),
		['class' => 'list-group-item']	
	);
}