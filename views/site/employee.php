<?php
use yii\helpers\Url;
use app\models\CaptureSearch;
/* @var $this yii\web\View */
$this->title = 'JO AND Z App';
?>
<div class="admin-index">

    <div class="jumbotron" data-intro='Menu principal rapide vers les fonctions les plus utilisées' data-position='bottom'>
        <h1>Bienvenue</h1>

        <p class="lead">Vous avez accès à toutes les fonctions de gestion des commandes et des travaux.</p>

        <p>
			<a class="btn btn-lg btn-primary" href="<?= Url::to(['/order/document/create-bid']) ?>">Nouveau devis</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create']) ?>">Nouvelle commande</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create-ticket']) ?>">Vente Comptoir</a>
			
			<?= $this->render('_form', ['model' => new CaptureSearch()]); ?>
		</p>
    </div>

    <div class="body-content">

		<div class="row">

			<div class="col-lg-4">

				<h3>Commandes</h3>

				<ul>
				    <li><a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Sales Tickets')?></a>
						<a href="<?= Url::to(['/order/document/create-ticket']) ?>"><span class="label label-success"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
				    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a>
						<a href="<?= Url::to(['/order/document/create-bid']) ?>"><span class="label label-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
				    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Order')?></a>
						<a href="<?= Url::to(['/order/document/create']) ?>"><span class="label label-success"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a>
						<a href="<?= Url::to(['/order/document/create-bom']) ?>"><span class="label label-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'BOM')?></span></a></li>
				</ul>

				<ul>
				    <li><a href="<?= Url::to(['/order/document/refunds', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Refunds')?></a>
						<a href="<?= Url::to(['/order/document/create-refund']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
				    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a>
						<a href="<?= Url::to(['/order/document/create-bill']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
				    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a>
						<a href="<?= Url::to(['/order/document/create-credit']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
				</ul>
			</div>


			<div class="col-lg-4">

				<h3>Listes</h3>

				<ul>
				    <li><a href="<?= Url::to(['/store/client/']) ?>"><?= Yii::t('store', 'Customers')?></a>
					<a href="<?= Url::to(['/store/client/new']) ?>"><span class="label label-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>

				</ul>

				<ul>
				    <li><a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a></li>
				    <li><a href="<?= Url::to(['/store/price']) ?>"><?= Yii::t('store', "Price Lists")?></a></li>
				    <li><a href="<?= Url::to(['/store/price-list']) ?>"><?= Yii::t('store', "Composite Price Lists")?></a></li>
				</ul>

			</div>

			<div class="col-lg-4">

				<h3>Comptabilité</h3>

				<div data-intro='Menu secondaire vers options de recherches et actions'>

					<ul>
					    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
					    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
					</ul>

				</div>

			</div>

		</div>

    </div>
</div>
