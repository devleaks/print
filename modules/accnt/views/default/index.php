<?php

use app\models\Parameter;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('store', 'Accounting');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accnt-default-index">

    <h1><?= Yii::t('store', 'Accounting') ?></h1>

<div class="row">
	<div class="col-lg-6">
		<ul>
	        <li><a href="<?= Url::to(['/accnt/cash/list']) ?>"><?= Yii::t('store', 'Cash')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/summary']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/cash/monthly']) ?>"><?= Yii::t('store', 'Monthly Cash Summary')?></a></li>
		</ul>
		<ul>
		    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/credits']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/refunds']) ?>"><?= Yii::t('store', 'Refunds')?></a></li>
		</ul>
		<ul>
		    <li><a href="<?= Url::to(['/order/document/bulk']) ?>"><?= Yii::t('store', 'Bill Orders')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
		</ul>
		<ul>
		    <li><a href="<?= Url::to(['/accnt/bill']) ?>"><?= Yii::t('store', 'Unpaid Bills')?></a></li>
        <li><a href="<?= Url::to(['/accnt/bill/others']) ?>"><?= Yii::t('store', 'Open tickets')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/account/create']) ?>"><?= Yii::t('store', 'Add payment with no sale')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/payment/credit-list']) ?>"><?= Yii::t('store', 'Reimburse client credit')?></a></li>
		</ul>
	</div>

	<div class="col-lg-6">
		<ul>
		    <li><a href="<?= Url::to(['/accnt/extraction']) ?>"><?= Yii::t('store', 'Monthly Extraction')?></a>
		<?= '( '.Html::a(Parameter::isTrue('application', 'new_accounting') ? 'Winbook' : 'Popsy', Url::to(['/accnt/extraction/sam'])).' )' ?></li>
		    <li><a href="<?= Url::to(['/accnt/pdf', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Documents to Print')?></a></li>
		</ul>
		<ul>
		    <li><i class="glyphicon glyphicon-warning-sign text-danger"></i> <a href="<?= Url::to(['/order/document/', 'sort' => '-updated_at']) ?>"><strong><?= Yii::t('store', 'Manage all documents')?></strong></a></li>
		    <li><a href="<?= Url::to(['/store/client', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Clients')?></a></li>
		    <li><a href="<?= Url::to(['/accnt/default/control']) ?>"><?= Yii::t('store', 'Checks')?></a></li>
		</ul>
		<ul>
		    <li><a href="<?= Url::to(['/accnt/bank']) ?>"><?= Yii::t('store', 'Bank Slips')?></a></li>
			<li><a href="<?= Url::to(['/stats/archive/']) ?>"><?= Yii::t('store', 'Bilans mensuels passés') ?></a></li>
		</ul>
	</div>
</div>

<br/>
<br/>
<br/>

<div class="panel panel-danger">
	<div class="panel-heading">
		<a name="dangerousops"></a><h3 class="panel-title">Opérations dangereuses</h3>
		<span class="pull-right clickable panel-collapsed"><i class="glyphicon glyphicon-chevron-down"></i></span>
	</div>
	<div class="panel-body collapse">

<div class="alert alert-danger" style="text-indent:-70px;padding-left:80px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Attention</strong>: Ces écrans manipulent directement les données brutes.
	Ils sont destinés à rectifier des erreurs de frappes et autres en modifiant directement les données.
	Une mauvaise manipulation dans ces écrans peut compromettre le fonctionnement général de l'application ou introduire des erreurs dans la comptabilité.
</div>

	<ul>
	    <li><a class="text-danger" href="<?= Url::to(['/accnt/account/list']) ?>"><?= Yii::t('store', 'Remove payment with no sale')?></a></li>
	    <li><a class="text-danger" href="<?= Url::to(['/order/document/index', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Payments')?></a> - Manipulations directes</li>
	    <li><a class="text-danger" href="<?= Url::to(['/accnt/cash/index', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Cash')?></a> - Manipulations directes</li>
	</ul>

	</div>
</div>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PANEL'); ?>
jQuery(function ($) {
    $('.panel-heading span.clickable').on("click", function (e) {
        if ($(this).hasClass('panel-collapsed')) {
            // expand the panel
            $(this).parents('.panel').find('.panel-body').slideDown();
            $(this).removeClass('panel-collapsed');
            $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
        else {
            // collapse the panel
            $(this).parents('.panel').find('.panel-body').slideUp();
            $(this).addClass('panel-collapsed');
            $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        }
    });
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PANEL'], yii\web\View::POS_END);
