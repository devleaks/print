<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;

?>
<div class="order-default-index">
    <h1><?= Yii::t('store', 'Orders') ?></h1>


<div class="row">
	<div class="col-lg-6">
		<h3>Ajouter</h3>
		<ul>
		    <li><a href="<?= Url::to(['/order/document/create-ticket']) ?>"><strong><?= Yii::t('store', 'New ticket')?></strong></a></li>
		    <li><a href="<?= Url::to(['/order/document/create-bid']) ?>"><?= Yii::t('store', 'Enter new bid')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/create-bom']) ?>"><?= Yii::t('store', 'Enter new bill of materials')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/create']) ?>"><?= Yii::t('store', 'Enter new order')?></a></li>
		</ul>

		<ul>
		    <li><a href="<?= Url::to(['/order/document/create-bill']) ?>"><?= Yii::t('store', 'Enter new bill')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/create-refund']) ?>"><?= Yii::t('store', 'New refund')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/create-credit']) ?>"><?= Yii::t('store', 'Enter new credit note')?></a></li>
		</ul>
	</div>
	<div class="col-lg-6">
		<h3>Listes</h3>
		<ul>
		    <li><a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Tickets')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Order')?></a></li>
		</ul>

		<ul>
		    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/refunds', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Refunds')?></a></li>
		    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">

		<h3>Comptabilité</h3>

		<div data-intro='Menu secondaire vers options de recherches et actions'>
			<?php if(in_array($role, ['manager', 'admin'])): ?>

			<ul>
			    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
			    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
			</ul>

			<?php elseif(in_array($role, ['compta'])): ?>
			<ul>
			    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a></li>
			    <li><a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Tickets')?></a></li>
			    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Orders')?></a></li>
			    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
			    <li><a href="<?= Url::to(['/order/document/refunds', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Refunds')?></a></li>
			    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
			</ul>

			<?php endif; ?>
		</div>

	</div>
</div>

</div>