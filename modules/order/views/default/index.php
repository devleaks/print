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

    <p>
    </p>
	<div data-intro='Menu secondaire vers options de recherches et actions'>
	<?php if(in_array($role, ['manager', 'admin'])): ?>
	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-ticket']) ?>"><strong><?= Yii::t('store', 'New ticket')?></strong></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bid']) ?>"><?= Yii::t('store', 'Enter new bid')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Manage bids')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bom']) ?>"><?= Yii::t('store', 'Enter new bill of materials')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/create']) ?>"><?= Yii::t('store', 'Enter new order')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Order management')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-bill']) ?>"><?= Yii::t('store', 'Enter new bill')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/document/create-credit']) ?>"><?= Yii::t('store', 'Enter new credit note')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	</ul>

	<?php elseif(in_array($role, ['compta'])): ?>
	<ul>
	    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Orders')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
	    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	</ul>

	<?php endif; ?>
	</div>

</div>