<?php

use yii\helpers\Url;

$this->title = Yii::t('store', 'Accounting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accnt-default-index">

    <h1><?= Yii::t('store', 'Accounting') ?></h1>

	<ul>
	    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/credits']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/create-refund']) ?>"><?= Yii::t('store', 'Create Refund')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/bill']) ?>"><?= Yii::t('store', 'Unpaid Bills')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/extraction']) ?>"><?= Yii::t('store', 'Monthly Extraction')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/pdf']) ?>"><?= Yii::t('store', 'Documents')?></a></li>
	    <li><a href="<?= Url::to(['/store/client', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Clients')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/cash/list']) ?>"><?= Yii::t('store', 'Cash')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/bank']) ?>"><?= Yii::t('store', 'Bank Slips')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/default/control']) ?>"><?= Yii::t('store', 'Checks')?></a></li>
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
	    <li><a href="<?= Url::to(['/accnt/payment/index', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Payments')?></a> - Manipulations directes</li>
	    <li><a href="<?= Url::to(['/accnt/cash/index', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Cash')?></a> - Manipulations directes</li>
	</ul>


</div>