<?php
use yii\helpers\Url;
use app\models\Backup;
/* @var $this yii\web\View */
$this->title = 'Labo JJ Micheli';
?>
<div class="admin-index">

    <div class="jumbotron" data-intro='Menu principal rapide vers les fonctions les plus utilisées' data-position='bottom'>
        <h1>Bienvenue</h1>

        <p class="lead">Vous pouvez enregistrer de nouvelles commandes.</p>
        <p class="lead">Vous avez accès à toutes les fonctions d'administration et de gestion de l'application.</p>

        <p>
			<a class="btn btn-lg btn-primary" href="<?= Url::to(['/order/document/create-bid']) ?>">Nouveau devis</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create']) ?>">Nouvelle commande</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create-ticket']) ?>">Comptoir</a>
			
			<?= $this->render('_form'); ?>
		</p>
    </div>

    <div class="body-content" data-intro='Menus secondaires vers la gestion courante' data-position='top'>

        <div class="row">
            <div class="col-lg-6" data-intro='Menu secondaire vers la gestion courante des commandes, etc.' data-position='right'>
                <h2>Gestion des Commandes</h2>

                <p>Inscrire de nouveaux devis, de nouvelles commandes, gérer leur suivi...</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/order/']) ?>">Commandes &raquo;</a></p>
				<p>
					&raquo; <a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a>
					&raquo; <a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Orders')?></a>
					&raquo; <a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a>
				</p>
            </div>
            <div class="col-lg-6">
                <h2>Gestion des Travaux</h2>

                <p>Travaux à faire, travaux en cours, état d'avancement des travaux d'une commande.</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/work/']) ?>">Travaux &raquo;</a></p>
				<p>
					&raquo; <a href="<?= Url::to(['/work/work/', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Manage all works')?></a>
				</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h2>Gestion du Magasin</h2>

                <p>Gestion des clients, gestion des articles, gestion des tâches à accomplir.</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/store/']) ?>">Magasin &raquo;</a></p>
				<p data-intro='Menu rapide vers actions les plus courantes'>
					&raquo; <a href="<?= Url::to(['/store/client/']) ?>"><?= Yii::t('store', 'Customers')?></a>
					&raquo; <a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a>
					&raquo; <a href="<?= Url::to(['/store/price']) ?>"><?= Yii::t('store', "Price Lists")?></a>
					&raquo; <a href="<?= Url::to(['/store/price-list']) ?>"><?= Yii::t('store', "Composite Price Lists")?></a>
					<?php if(Backup::getDbName('yii2print')): ?>
					&raquo; <a href="<?= Url::to(['/stats/']) ?>"><?= Yii::t('store', "Stats")?></a>
					<?php endif; ?>
				</p>
            </div>
            <div class="col-lg-6">
                <h2>Gestion de l'Application</h2>

                <p>Gestion de l'accès à l'application, gestion des utilisateurs de l'application, gestion des paramètres.</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/admin/']) ?>">Application &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
