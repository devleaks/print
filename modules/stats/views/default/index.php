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

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>stats/masonry/bricks"><?= Yii::t('store', 'Masonry')?></a></li>
		<li><a href="<?= Url::to(['/stats/item/category']) ?>"><?= Yii::t('store', 'Articles achetés par catégories') ?></a></li>
		<li>Tailles demandées</li>
	</ul>

<h3>Statistiques sur les clients et les commandes</h3>

	<ul>
		<li>Commandes par clients (nombres, montants, moyennes)</li>
		<li>Commandes par clients (fréquence, périodicité)</li>
		<li>Commandes dans le temps</li>
		<li>Argent dans le temps</li>
	</ul>

<h3>Statistiques sur les travaux et les tâches</h3>

	<ul>
		<li>Durée moyenne entre commande et début de réalisation</li>
		<li>Durée moyenne entre commande et fin de réalisation</li>
		<li>Durée moyenne entre début et fin de réalisation</li>
		<li>Durée moyenne par tâche</li>
	</ul>

</div>
