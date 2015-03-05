<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stats-default-index">
    <h1><?= $this->title ?></h1>

    <p>
    </p>

<h3>Statistiques sur les articles</h3>

<h4>Ligne du Temps</h4>

	<ul>
		<li><a href="<?= Url::to(['/stats/event/']) ?>"><?= Yii::t('store', 'Evénements sur la ligne du temps') ?></a></li>
	</ul>


<h4>Articles</h4>

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

<h3>Statistiques sur les clients et les commandes</h3>

	<ul>
		<li><a href="<?= Url::to(['/stats/order/']) ?>"><?= Yii::t('store', 'Commandes par clients (nombres, montants, moyennes)')?></a></li>
		<li>Commandes par clients (fréquence, périodicité)</li>
		<li><a href="<?= Url::to(['/stats/order/by-day']) ?>"><?= Yii::t('store', 'Commandes par jour')?></a></li>
		<li>Argent dans le temps</li>
	</ul>

<h3>Statistiques sur les travaux et les tâches</h3>

	<ul>
		<li>Durée moyenne entre commande et début de réalisation</li>
		<li>Durée moyenne entre commande et fin de réalisation</li>
		<li><a href="<?= Url::to(['/stats/work/']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de réalisation')?></a></li>
		<li><a href="<?= Url::to(['/stats/work/lines']) ?>"><?= Yii::t('store', 'Durée moyenne entre début et fin de tâche')?></a></li>
		<li>Durée moyenne par tâche</li>
	</ul>

</div>
