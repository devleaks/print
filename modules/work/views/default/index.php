<?php
use app\models\Task;
use app\models\Work;
use app\models\WorkLine;

$this->title = Yii::t('store', 'Works');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-default-index">
	
<h1><?= Yii::t('store', 'Works and Tasks') ?></h1>

<div class="col-md-6 col-md-offset-3">

	<div class="panel panel-default" data-intro='Tâches organisées par type (découpes, impressions...)' data-position='left'>
  		<div class="panel-heading"><?= Yii::t('store', 'Tasks by Type')?></div>
  		<div class="panel-body">
			<div class="list-group">
				<?= $this->render('_tasks_by_type') ?>
			</div>
  		</div>
	</div>

	<div class="panel panel-default" data-intro='Tâches organisées par commandes (1 commande = 1 travail = plusieurs tâches à accomplir)' data-position='left'>
  		<div class="panel-heading"><?= Yii::t('store', 'Works') . ' ('.Yii::t('store', 'Orders').')'?></div>

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

	<div class="alert alert-default" data-intro="Légende: Les couleurs sont en fonction de la date" data-position='left'>
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<div style="width:23%;display:inline-block">
			<span class="badge alert-danger"><i class="glyphicon glyphicon-warning-sign"></i> 22</span> En retard
		</div>
		<div style="width:23%;display:inline-block">
			<span class="badge alert-default"><i class="glyphicon glyphicon-play-circle"></i> 22</span> A faire
		</div>
		<div style="width:23%;display:inline-block">
			<span class="badge alert-warning"><i class="glyphicon glyphicon-inbox"></i> 22</span> En cours
		</div>
		<div style="width:23%;display:inline-block">
			<span class="badge alert-success"><i class="glyphicon glyphicon-ok"></i> 22</span> Terminé
		</div>
	</div>

	
	<div class="panel panel-default" data-intro="Tâches organisées par date de livraison de la commande ou d'une partie de la commande" data-position='left'>
  		<div class="panel-heading"><?= Yii::t('store', 'Tasks by Date')?></div>
  		<div class="panel-body">
			<div class="list-group">
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/" class="list-group-item"><?= WorkLine::getBadge(-2) ?><?= Yii::t('store', 'All Tasks')?></a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/mine" class="list-group-item"><?= Yii::t('store', 'My Tasks')?></a>

				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=-1" class="list-group-item"><?= WorkLine::getBadge(-1) ?>Tâches en retard</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list" class="list-group-item"><?= WorkLine::getBadge(0) ?>Tâches pour aujourd'hui</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=1" class="list-group-item"><?= WorkLine::getBadge(1) ?>Tâches pour demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=2" class="list-group-item"><?= WorkLine::getBadge(2) ?>Tâches pour après-demain</a>
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/list?id=7" class="list-group-item"><?= WorkLine::getBadge(7) ?>Tâches pour les 7 prochains jours</a>
			</div>
  		</div>
	</div>
	
	<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Les tâches terminées (décomptes en vert <span class="badge alert-success"><i class="glyphicon glyphicon-ok"></i> 22</span> ci-dessus)
	ne sont pas affichées dans les listes des tâches à accomplir.</p>
	<p>Pour voir les tâches terminées, sélectionner les travaux.</p>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
				<a href="<?= Yii::$app->homeUrl ?>work/work-line/to-cut">Découpe (expériemental)</a>
				<a href="<?= Yii::$app->homeUrl ?>work/master">Renforts en stock</a>
		</div>
	</div>

</div>

</div>
