<?php
use yii\helpers\Url;
use app\models\CaptureSearch;
/* @var $this yii\web\View */
$this->title = 'JO AND Z App';
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
			
			<?= $this->render('_form', ['model' => new CaptureSearch()]); ?>
		</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Gestion des Commandes</h2>

                <p>Inscrire de nouveaux devis, de nouvelles commandes, gérer leur suivi...</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>order/">Commandes &raquo;</a></p>
				<p>
					&raquo; <a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a>
					&raquo; <a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Orders')?></a>
					&raquo; <a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a>
				</p>
            </div>
            <div class="col-lg-4">
                <h2>Gestion des Travaux</h2>

                <p>Travaux à faire, travaux en cours, état d'avancement des travaux d'une commande.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>work/">Travaux &raquo;</a></p>
				<p>
					&raquo; <a href="<?= Url::to(['/work/work/', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Manage all works')?></a>
				</p>
            </div>
            <div class="col-lg-4">
                <h2>Gestion du Magasin</h2>

                <p>Gestion des clients, gestion des articles, gestion des tâches à accomplir.</p>

                <p><a class="btn btn-default" href="<?=Yii::$app->homeUrl?>store/">Magasin &raquo;</a></p>
				<p>
					&raquo; <a href="<?= Url::to(['/store/client/']) ?>"><?= Yii::t('store', 'Customers')?></a>
					&raquo; <a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a>
					&raquo; <a href="<?= Url::to(['/store/price']) ?>"><?= Yii::t('store', "Price Lists")?></a>
					&raquo; <a href="<?= Url::to(['/store/price-list']) ?>"><?= Yii::t('store', "Composite Price Lists")?></a>
				</p>
            </div>
        </div>

    </div>
</div>
