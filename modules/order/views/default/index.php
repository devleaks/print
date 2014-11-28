<?php
$this->title = Yii::t('store', 'Orders');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-default-index">
    <h1><?= Yii::t('store', 'Orders') ?></h1>

    <p>
    </p>
	<div data-intro='Menu secondaire vers options de recherches et actions'>
	<?php if(in_array(Yii::$app->user->identity->role, ['manager', 'admin'])): ?>
	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/create-bid"><?= Yii::t('store', 'Enter new bid')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/bids"><?= Yii::t('store', 'Manage bids')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/create"><?= Yii::t('store', 'Enter new order')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/orders"><?= Yii::t('store', 'Order management')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/create-bill"><?= Yii::t('store', 'Enter new bill')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/bills"><?= Yii::t('store', 'Bills')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/create-credit"><?= Yii::t('store', 'Enter new credit note')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/credits"><?= Yii::t('store', 'Credit Notes')?></a></li>
	</ul>

	<?php else: ?>
	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>order/order/orders"><?= Yii::t('store', 'Orders')?></a></li>
	</ul>
	<?php endif; ?>
	</div>

</div>