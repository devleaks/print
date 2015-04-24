<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Management');
$this->params['breadcrumbs'][] = $this->title;
$role = null;
if(isset(Yii::$app->user))
	if(isset(Yii::$app->user->identity))
		if(isset(Yii::$app->user->identity->role))
			$role = Yii::$app->user->identity->role;
?>
<div class="store-default-index">
    <h1><?= Yii::t('store', 'Management') ?></h1>

    <p>
    </p>

	<?php if(in_array($role, ['manager', 'admin'])): ?>
	<div data-intro='Menu secondaire vers options de recherches et actions'>
	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-ticket']) ?>"><strong><?= Yii::t('store', 'New ticket')?></strong></a></li>
	    <li><a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Tickets')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bid']) ?>"><?= Yii::t('store', 'Enter new bid')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Manage bids')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bom']) ?>"><?= Yii::t('store', 'Enter new bill of materials')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/create']) ?>"><strong><?= Yii::t('store', 'Enter new order')?></strong></a></li>
	    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Order management')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bill']) ?>"><?= Yii::t('store', 'Enter new bill')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-refund']) ?>"><?= Yii::t('store', 'New refund')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/refunds', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Refunds')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-credit']) ?>"><?= Yii::t('store', 'Enter new credit note')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	</ul>

	<ul>
	    <li><i class="glyphicon glyphicon-warning-sign text-danger"></i> <a href="<?= Url::to(['/order/document/', 'sort' => '-updated_at']) ?>"><strong><?= Yii::t('store', 'Manage all documents')?></strong></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/work/work/']) ?>"><?= Yii::t('store', 'Manage all works')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/bulk', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bulk Bill')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/']) ?>"><strong><?= Yii::t('store', 'Accounting')?></strong></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/store/client/']) ?>"><?= Yii::t('store', 'Customers')?></a></li>
	</ul>
	</div>

	<ul data-intro="Menu pour les données de référence" data-position='top'>
	    <li><?= Yii::t('store', 'Reference tables')?></li>
			<ul>
			    <li><a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a></li>
			    <li><a href="<?= Url::to(['/store/price']) ?>"><?= Yii::t('store', "Price Lists")?></a></li>
			    <li><a href="<?= Url::to(['/store/price-list']) ?>"><?= Yii::t('store', "Composite Price Lists")?></a></li>
			    <li><a href="<?= Url::to(['/store/provider']) ?>"><?= Yii::t('store', "Frame Providers")?></a></li>
			    <li><a href="<?= Url::to(['/store/task']) ?>"><?= Yii::t('store', 'Tasks')?></a></li>
			    <li><a href="<?= Url::to(['/store/item/tasks']) ?>"><?= Yii::t('store', "Items' Associated Tasks")?></a></li>
			</ul>
	</ul>
	<?php else: ?>
	<ul>
	    <li><a href="<?= Url::to(['/order/document/orders']) ?>"><?= Yii::t('store', 'Orders')?></a></li>
	</ul>
	<?php endif; ?>

</div>
