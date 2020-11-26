<?php
use yii\helpers\Url;
use app\models\Backup;
use app\models\CaptureSearch;
use app\models\Document;
use app\models\WebsiteOrder;
use app\models\Parameter;

/* @var $this yii\web\View */
$this->title = 'Jo and Z';

$opens = Document::find()->andWhere(['id' => WebsiteOrder::find()->select('document_id'), 'status' => Document::STATUS_OPEN])->count();
$errors = WebsiteOrder::find()->andWhere(['status' => [WebsiteOrder::STATUS_WARN]])->count();

$allowed = Parameter::getTextValue('application','stats');
$allowed_arr = $allowed != '' ? explode(',', $allowed) : [];
$viewstats = in_array(Yii::$app->user->identity->username, $allowed_arr);

?>
<div class="admin-index">

    <div class="jumbotron" data-intro='Menu principal rapide vers les fonctions les plus utilisées' data-position='bottom'>
        <p>
			<a class="btn btn-lg btn-primary" href="<?= Url::to(['/order/document/create-bid']) ?>">Nouveau devis</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create']) ?>">Nouvelle commande</a>
			<a class="btn btn-lg btn-success" href="<?= Url::to(['/order/document/create-ticket']) ?>">Vente comptoir</a>
			
			<?= $this->render('_form', ['model' => new CaptureSearch()]); ?>
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
					<?php if($opens > 0): ?>
					&raquo; <a href="<?= Url::to(['/order/document/website', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'New Web Orders')?></a>
					<span class="badge alert-success"><i class="glyphicon glyphicon-warning-sign"></i><?= $opens ?></span>
					<?php endif; ?>
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
				</p>
            </div>
            <div class="col-lg-6">
                <h2>Comptabilité</h2>

                <p>Gestion de la comptabilité, de la caisse, et des paiements.</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/accnt/']) ?>">Comptabilité &raquo;</a></p>
				<p data-intro='Menu rapide vers actions les plus courantes'>
					&raquo; <a href="<?= Url::to(['/accnt/pdf', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'PDF Documents to Print')?></a></li>
					&raquo; <a href="<?= Url::to(['/accnt/cash']) ?>"><?= Yii::t('store', 'Cash')?></a></li>
				</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h2>Gestion de l'Application</h2>

                <p>Gestion de l'accès à l'application, gestion des utilisateurs de l'application, gestion des paramètres.</p>

                <p><a class="btn btn-primary" href="<?= Url::to(['/admin/']) ?>">Application &raquo;</a></p>
            </div>
			<?php if(defined('YII_DEVLEAKS')): ?>
            <div class="col-lg-6">
                <h2>Développements</h2>

                <p>Liens rapides vers les développements.</p>

				<p data-intro='Menu rapide vers actions les plus courantes'>
					&raquo; <a href="<?= Url::to(['/order/document/', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'All documents')?></a>
					&raquo; <a href="<?= Url::to(['/stats/dashboard']) ?>"><?= Yii::t('store', "Dashboard")?></a>
                    <?php if($viewstats): ?>
					&raquo; <a href="<?= Url::to(['/stats/']) ?>"><?= Yii::t('store', "Stats")?></a>
                <?php endif; ?>
				</p>
            </div>
			<?php endif; ?>
        </div>
    </div>
</div>
