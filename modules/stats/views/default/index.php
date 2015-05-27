<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stats-default-index">

    <div class="jumbotron">
	    <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/stats/dashboard']) ?>"><?= Yii::t('store', 'Dashboard')?></a></p>
    </div>


<div class="row">
	<div class="col-lg-6">

		<h3>Articles</h3>

			<ul>
				<li><a href="<?= Url::to(['/stats/item/item']) ?>"><?= Yii::t('store', 'Articles achetés') ?></a></li>
				<li><a href="<?= Url::to(['/stats/item/category']) ?>"><?= Yii::t('store', 'Articles achetés par categorie') ?></a></li>
				<li><a href="<?= Url::to(['/stats/item/yii-category']) ?>"><?= Yii::t('store', 'Articles achetés par Yii Catégories') ?></a></li>
			</ul>

			<h4>Tailles</h4>

				<ul>
				    <li><a href="<?= Url::to(['/stats/masonry/frames']) ?>"><?= Yii::t('store', 'Tailles demandées')?></a>
					 (<a href="<?= Url::to(['/stats/masonry/frames-straightened']) ?>"><?= Yii::t('store', 'straightened')?></a>)</li>
				    <li><a href="<?= Url::to(['/stats/masonry/bricks']) ?>"><?= Yii::t('store', 'Représentation graphique de toutes les tailles')?></a></li>
				</ul>

	</div>


	<div class="col-lg-6">

		<h3>Clients</h3>

			<ul>
				<li><a href="<?= Url::to(['/stats/order/']) ?>"><?= Yii::t('store', 'Commandes par clients (nombres, montants, moyennes)')?></a></li>
				<li>Commandes par clients (fréquence, périodicité)</li>
				<li><a href="<?= Url::to(['/stats/order/by-day']) ?>"><?= Yii::t('store', 'Commandes par jour')?></a></li>
				<li>Argent dans le temps</li>
			</ul>
		</ul>

	</div>
</div>

<div class="row">
	<div class="col-lg-6">

		<h3>Commandes</h3>

			<ul>
				<li><a href="<?= Url::to(['/stats/order/']) ?>"><?= Yii::t('store', 'Commandes par clients (nombres, montants, moyennes)')?></a></li>
				<li>Commandes par clients (fréquence, périodicité)</li>
				<li><a href="<?= Url::to(['/stats/order/by-day']) ?>"><?= Yii::t('store', 'Commandes par jour')?></a></li>
				<li>Argent dans le temps</li>
			</ul>
		</ul>

	</div>

	<div class="col-lg-6">

		<h3>Travaux & Tâches</h3>

			<ul>
				<li>Durée moyenne entre commande et début de réalisation</li>
				<li>Durée moyenne entre commande et fin de réalisation</li>
				<li><a href="<?= Url::to(['/stats/work/']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de réalisation')?></a></li>
				<li><a href="<?= Url::to(['/stats/work/lines']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de tâche')?></a></li>
				<li>Durée moyenne par tâche</li>
			</ul>
		
	</div>
</div>



	<div class="row">
		<div class="col-lg-6">

			<h3>Ligne du Temps</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/event/']) ?>"><?= Yii::t('store', 'Evénements sur la ligne du temps') ?></a></li>
				</ul>

	</div>

	<div class="col-lg-6">

		<h3>?</h3>

		<ul>
			<li>?</li>
		</ul>

	</div>
</div>

</div>

