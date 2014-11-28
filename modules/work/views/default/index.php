<?php
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;
use app\assets\BadgeAsset;

$this->title = Yii::t('store', 'Works');
$this->params['breadcrumbs'][] = $this->title;

BadgeAsset::register($this);

?>
<div class="work-default-index">
	
<h1><?= Yii::t('store', 'Works and Tasks') ?></h1>

<div class="col-md-6 col-md-offset-3">

	<div class="panel panel-default">
  		<div class="panel-heading"><?= Yii::t('store', 'Tasks')?></div>
  		<div class="panel-body">
			<div class="list-group">
				<?= $this->render('_tasks_by_type') ?>
			</div>
  		</div>
	</div>

	<div class="panel panel-default">
  		<div class="panel-heading"><?= Yii::t('store', 'Works')?></div>

  		<div class="panel-body">
			<div class="list-group">
				<a href="<?= Yii::$app->homeUrl ?>work/work/" class="list-group-item"><?= Work::getBadge(-2) ?><?= Yii::t('store', 'All Works')?></a>
				<a href="<?= Yii::$app->homeUrl ?>work/work/list?id=-1" class="list-group-item"><?= Work::getBadge(-1) ?>Travaux en retard</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work/list" class="list-group-item"><?= Work::getBadge(0) ?>Travaux pour aujourd'hui</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work/list?id=1" class="list-group-item"><?= Work::getBadge(1) ?>Travaux pour demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work/list?id=2" class="list-group-item"><?= Work::getBadge(2) ?>Travaux pour après-demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work/list?id=7" class="list-group-item"><?= Work::getBadge(7) ?>Travaux pour les 7 prochains jours</a>
			</div>
  		</div>
	</div>
	
	<div class="panel panel-default">
  		<div class="panel-heading"><?= Yii::t('store', 'Tasks')?></div>
  		<div class="panel-body">
			<div class="list-group">
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/" class="list-group-item"><?= Work::getBadge(-2) ?><?= Yii::t('store', 'All Tasks')?></a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/mine" class="list-group-item"><?= Yii::t('store', 'My Tasks')?></a>

				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=-1" class="list-group-item"><?= Work::getBadge(-1) ?>Tâches en retard</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list" class="list-group-item"><?= Work::getBadge(0) ?>Tâches pour aujourd'hui</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=1" class="list-group-item"><?= Work::getBadge(1) ?>Tâches pour demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=2" class="list-group-item"><?= Work::getBadge(2) ?>Tâches pour après-demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=7" class="list-group-item"><?= Work::getBadge(7) ?>Tâches pour les 7 prochains jours</a>
			</div>
  		</div>
	</div>

</div>

</div>
