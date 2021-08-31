<?php
/* @var $this yii\web\View */
$this->title = 'Colorfields';
?>
<div class="admin-index">

    <div class="jumbotron">
        <h1>Bienvenue</h1>

        <p class="lead">Vous avez accès à toutes les fonctions de gestion des travaux des commandes.</p>

    </div>

    <div class="body-content">

        <div class="row">
			<div class="col-md-6 col-md-offset-3">
                <h2>Gestion des Travaux</h2>

                <p>Travaux à faire, travaux en cours, état d'avancement des travaux d'une commande.</p>

                <p><a class="btn btn-primary" href="<?=Yii::$app->homeUrl?>work/">Travaux &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
