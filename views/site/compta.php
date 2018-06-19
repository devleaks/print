<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Parameter;

/* @var $this yii\web\View */
$this->title = 'MikeMuka App';
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
				<p>
					&raquo; <a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?>
					&raquo; <a href="<?= Url::to(['/accnt/extraction']) ?>"><?= Yii::t('store', 'Monthly Extraction')?>
					<?= '( '.Html::a(Parameter::isTrue('application', 'new_accounting') ? 'Winbook' : 'Popsy', Url::to(['/accnt/extraction/sam'])).' )' ?></a>
					&raquo; <a href="<?= Url::to(['/accnt/pdf', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Documents')?></a></li>
					&raquo; <a href="<?= Url::to(['/accnt/cash']) ?>"><?= Yii::t('store', 'Cash')?></a></li>
				</p>
            </div>

            <div class="col-lg-6">
                <h2>Documents</h2>

                <p>Devis, commandes, factures...</p>

                <p><a class="btn btn-primary" href="<?=Yii::$app->homeUrl?>order/">Documents &raquo;</a></p>
				<p>
	    			&raquo; <a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Tickets')?></a>
	    			&raquo; <a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Orders')?></a>
	    			&raquo; <a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a>
				</p>
            </div>
        </div>

    </div>
</div>