<?php
/* @var $this yii\web\View */
$this->title = 'Labo JJ Micheli @Work';
?>
<div class="admin-index">

    <div class="jumbotron">
        <h1>Bienvenue</h1>

        <p class="lead">Vous avez accès à toutes les fonctions de gestion comptable.</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Comptabilité</h2>

                <p>Extractions quotidienne et mensuelle, factures impayées...</p>

                <p><a class="btn btn-primary" href="<?=Yii::$app->homeUrl?>accnt/">Comptabilité &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2>Documents</h2>

                <p>Devis, commandes, factures...</p>

                <p><a class="btn btn-primary" href="<?=Yii::$app->homeUrl?>order/">Documents &raquo;</a></p>
            </div>
        </div>

    </div>
</div>