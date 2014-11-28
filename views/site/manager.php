<?php
/* @var $this yii\web\View */
$this->title = 'Labo JJ Micheli @Work';
?>
<div class="admin-index">

    <div class="jumbotron">
        <h1>Bienvenue</h1>

        <p class="lead">Vous pouvez enregistrer de nouveaux devis et de nouvelles commandes.</p>
        <p class="lead">Vous avez accès à toutes les fonctions de gestion.</p>

        <p>
			<a class="btn btn-lg btn-primary" href="<?=Yii::$app->homeUrl?>order/order/create-bid">Nouveau devis</a>
			<a class="btn btn-lg btn-success" href="<?=Yii::$app->homeUrl?>order/order/create">Nouvelle commande</a>
			
			<?= $this->render('_form'); ?>
		</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Gestion des Commandes</h2>

                <p>Inscrire de nouveaux devis, de nouvelles commandes, gérer leur suivi...</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>order/">Commandes &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Gestion des Travaux</h2>

                <p>Travaux à faire, travaux en cours, état d'avancement des travaux d'une commande.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>work/">Travaux &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Gestion du Magasin</h2>

                <p>Gestion des clients, gestion des articles, gestion des tâches à accomplir.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>store/">Magasin &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
