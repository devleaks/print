<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-default-index">
    <h1><?= Yii::t('store', 'Management') ?></h1>

    <p>
    </p>

	<?php if(in_array(Yii::$app->user->identity->role, ['manager', 'admin'])): ?>
	<ul>
	    <li><a href="<?= Url::to(['/order/order/create-bid']) ?>"><?= Yii::t('store', 'Enter new bid')?></a></li>
	    <li><a href="<?= Url::to(['/order/order/bids']) ?>"><?= Yii::t('store', 'Manage bids')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/order/create']) ?>"><?= Yii::t('store', 'Enter new order')?></a></li>
	    <li><a href="<?= Url::to(['/order/order/orders']) ?>"><?= Yii::t('store', 'Order management')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/order/create-bill']) ?>"><?= Yii::t('store', 'Enter new bill')?></a></li>
	    <li><a href="<?= Url::to(['/order/order/bills']) ?>"><?= Yii::t('store', 'Bills')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/order/create-credit']) ?>"><?= Yii::t('store', 'Enter new credit note')?></a></li>
	    <li><a href="<?= Url::to(['/order/order/credits']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/order/order/', 'sort' => 'updated_at']) ?>"><strong><?= Yii::t('store', 'Manage all documents')?></strong></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/work/work/']) ?>"><?= Yii::t('store', 'Manage all works')?></a></li>
	</ul>

	<ul>
	    <li><a href="<?= Url::to(['/store/client/']) ?>"><?= Yii::t('store', 'Customers')?></a></li>
	    <li><?= Yii::t('store', 'Reference tables')?></li>
			<ul>
			    <li><a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a></li>
			    <li><a href="<?= Url::to(['/store/task']) ?>"><?= Yii::t('store', 'Tasks')?></a></li>
			    <li><a href="<?= Url::to(['/store/item/tasks']) ?>"><?= Yii::t('store', "Items' Associated Tasks")?></a></li>
			</ul>
	</ul>
	<?php else: ?>
	<ul>
	    <li><a href="<?= Url::to(['/order/order/orders']) ?>"><?= Yii::t('store', 'Orders')?></a></li>
	</ul>
	<?php endif; ?>

</div>
