<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Application');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-default-index">
    <h1><?= $this->title ?></h1>

    <p>
    </p>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>user/admin/"><?= Yii::t('store', 'User Accounts')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/parameter/"><?= Yii::t('store', 'Parameters')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/backup/"><?= Yii::t('store', 'Database Backup')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/backup/restore"><?= Yii::t('store', 'Restore Database from Backup of Production')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/sequence-data/"><?= Yii::t('store', 'Manage Sequence Numbers')?></a></li>
	</ul>

<br/>
<br/>
<br/>

<h3><span style="color: red;">Opérations dangereuses</span></h3>

<div class="alert alert-danger">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	Attention: Ces écrans manipulent directement les données brutes.
	Ils sont destinés à rectifier des erreurs de frappes et autres en modifiant directement les données.
	Une mauvaise manipulation dans ces écrans peut compromettre le fonctionnement général de l'application ou introduire des erreurs dans la comptabilité.
</div>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/index', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Payments')?></a> - Manipulations directes</li>
	    <li><a href="<?= Url::to(['/accnt/payment/multidoc']) ?>"><?= Yii::t('store', 'Remove payment for multiple documents')?></a> - Danger</li>
	    <li><a href="<?= Url::to(['/accnt/cash/index', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Cash')?></a> - Manipulations directes</li>
	</ul>

</div>
