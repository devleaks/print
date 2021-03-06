<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stats-index container">

    <div class="jumbotron">
	    <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/stats/dashboard']) ?>"><?= Yii::t('store', 'Dashboard')?></a></p>
    </div>

	<div class="row">

		<div class="col-lg-6">
			<h3>Commandes</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/order/by-day']) ?>"><?= Yii::t('store', 'Commandes par jour')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/by-month']) ?>"><?= Yii::t('store', 'Commandes par mois (HTVA)')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/billed']) ?>"><?= Yii::t('store', 'Facturé par mois (HTVA)')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/ca']) ?>"><?= Yii::t('store', "Chriffre d'affaire (factures-notes de crédit / comptoir-remboursements, HTVA)")?></a></li>
					<!-- li><a href="<?= Url::to(['/stats/order/by-week']) ?>"><?= Yii::t('store', 'Moyenne mobile par semaine (HTVA)')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/by-lang']) ?>"><?= Yii::t('store', 'Commandes par langue (HTVA)')?></a></li -->
				</ul>
		</div>


		<div class="col-lg-6">
			<h3>Clients</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/order/']) ?>"><?= Yii::t('store', 'Commandes par clients (nombres, montants, moyennes)')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/frequency']) ?>"><?= Yii::t('store', 'Commandes par clients (fréquence, périodicité)')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/nvb']) ?>"><?= Yii::t('store', 'Commandes par clients NVB')?></a></li>
					<li><a href="<?= Url::to(['/stats/order/nvb-by-month']) ?>"><?= Yii::t('store', 'Commandes par clients NVB par mois')?></a></li>
				</ul>
		</div>
	</div>


	<div class="row">

		<div class="col-lg-6">
			<h3>Travaux & Tâches</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/work/']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de réalisation')?></a></li>
					<li><a href="<?= Url::to(['/stats/work/lines']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de tâche')?></a></li>
<!--				<li>Durée moyenne entre commande et début de réalisation</li>
					<li>Durée moyenne entre commande et fin de réalisation</li>
					<li>Durée moyenne par tâche</li>
-->				</ul>		
			<h3>Analyse</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/bi/sales']) ?>"><?= Yii::t('store', 'Ventes') ?></a></li>
					<li><a href="<?= Url::to(['/stats/bi/items']) ?>"><?= Yii::t('store', 'Articles') ?></a></li>
					<li><a href="<?= Url::to(['/stats/bi/works']) ?>"><?= Yii::t('store', 'Travaux') ?></a></li>
				</ul>
		</div>

		<div class="col-lg-6">
			<h3>Articles</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/item/item']) ?>"><?= Yii::t('store', 'Articles achetés') ?></a></li>
					<li><a href="<?= Url::to(['/stats/item/category']) ?>"><?= Yii::t('store', 'Articles achetés par catégorie') ?></a></li>
					<li><a href="<?= Url::to(['/stats/item/yii-category']) ?>"><?= Yii::t('store', 'Articles achetés par catégorie «spéciale pour cette application»') ?></a></li>
				</ul>

				<h4>Tailles</h4>
					<ul>
					    <li><a href="<?= Url::to(['/stats/masonry/frames']) ?>"><?= Yii::t('store', 'Tailles demandées')?></a>
						 (<a href="<?= Url::to(['/stats/masonry/frames-straightened']) ?>"><?= Yii::t('store', 'straightened')?></a>)</li>
					    <li><a href="<?= Url::to(['/stats/masonry/bricks']) ?>"><?= Yii::t('store', 'Représentation graphique de toutes les commandes par année')?></a><br/>
						    <span style="font-size:smaller;font-style:italic;">Patience, c'est un peu plus lent... Patience, et cela apparaît...<span></li>
					</ul>
		</div>

	</div>


	<div class="row">

		<div class="col-lg-6">
			<h3>Ligne du Temps</h3>

				<ul>
					<li><a href="<?= Url::to(['/stats/event/']) ?>"><?= Yii::t('store', 'Evénements sur la ligne du temps') ?></a></li>
					<li><a href="<?= Url::to(['/stats/archive/']) ?>"><?= Yii::t('store', 'Bilans mensuels passés') ?></a></li>
				</ul>
		</div>

		<div class="col-lg-6">
		</div>
	
	</div>

</div>

