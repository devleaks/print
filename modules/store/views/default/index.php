<?php
use app\models\User;
use app\models\Document;
use app\models\Parameter;
use app\models\WebsiteOrder;
use yii\helpers\Url;

$this->title = Yii::t('store', 'Management');
$this->params['breadcrumbs'][] = $this->title;

$opens = Document::find()->andWhere(['id' => WebsiteOrder::find()->select('document_id'), 'status' => Document::STATUS_OPEN])->count();
$errors = WebsiteOrder::find()->andWhere(['status' => [WebsiteOrder::STATUS_WARN]])->count();

$allowed = Parameter::getTextValue('application','stats');
$allowed_arr = $allowed != '' ? explode(',', $allowed) : [];
$viewstats = in_array(Yii::$app->user->identity->username, $allowed_arr);

?>
<div class="order-default-index">
    <h1><?= Yii::t('store', 'Orders') ?></h1>


<div class="row">
	<div class="col-lg-6">
		<ul>
		    <li><a href="<?= Url::to(['/order/document/tickets', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Tickets')?></a>
				<a href="<?= Url::to(['/order/document/create-ticket']) ?>"><span class="label label-success"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		    <li><a href="<?= Url::to(['/order/document/bids', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bids')?></a>
				<a href="<?= Url::to(['/order/document/create-bid']) ?>"><span class="label label-success"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		    <li><a href="<?= Url::to(['/order/document/orders', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Order')?></a>
				<a href="<?= Url::to(['/order/document/create']) ?>"><span class="label label-success"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a>
				<a href="<?= Url::to(['/order/document/create-bom']) ?>"><span class="label label-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'BOM')?></span></a></li>
		</ul>

		<ul>
		    <li><a href="<?= Url::to(['/order/document/refunds', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Refunds')?></a>
				<a href="<?= Url::to(['/order/document/create-refund']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		    <li><a href="<?= Url::to(['/order/document/bills', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bills')?></a>
				<a href="<?= Url::to(['/order/document/create-bill']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		    <li><a href="<?= Url::to(['/order/document/credits', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Credit Notes')?></a>
				<a href="<?= Url::to(['/order/document/create-credit']) ?>"><span class="label label-warning"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		</ul>
	</div>


	<div class="col-lg-6">

		<h3>Gestion</h3>
		
		<ul>
		    <li><i class="glyphicon glyphicon-warning-sign text-danger"></i> <a href="<?= Url::to(['/order/document/', 'sort' => '-updated_at']) ?>"><strong><?= Yii::t('store', 'Manage all documents')?></strong></a></li>
		</ul>

		<ul>
			    <li><a href="<?= Url::to(['/work/work', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'All works')?></a></li>
		</ul>

		<ul>
		    	<li><a href="<?= Url::to(['/order/document/website', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Web Orders')?></a>
					<?php if($opens > 0): ?>
					<span class="badge alert-success"><i class="glyphicon glyphicon-warning-sign"></i><?= $opens ?></span>
					<?php endif; ?>
				</li>
			    <li><a href="<?= Url::to(['/order/website-order', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Transferts du site web')?></a>
					<?php if($errors > 0): ?>
					<span class="badge alert-warning"><i class="glyphicon glyphicon-warning-sign"></i><?= $errors ?></span>
					<?php endif; ?>
				</li>
		</ul>

	</div>
</div>

<div class="row">
	<div class="col-lg-6">

		<h3>Listes</h3>
		
		<ul>
		    <li><a href="<?= Url::to(['/store/client/', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Customers')?></a>
				<a href="<?= Url::to(['/store/client/new']) ?>"><span class="label label-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('store', 'Add')?></span></a></li>
		    <li><a href="<?= Url::to(['/store/client/mailing', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Mailing List')?></a></li>
		</ul>

		<ul>
		    <li><a href="<?= Url::to(['/store/item']) ?>"><?= Yii::t('store', 'Items')?></a></li>
		    <li><a href="<?= Url::to(['/store/price']) ?>"><?= Yii::t('store', "Price Lists")?></a></li>
		    <li><a href="<?= Url::to(['/store/price-list']) ?>"><?= Yii::t('store', "Composite Price Lists")?></a></li>
		</ul>
		<ul>
		    <li><a href="<?= Url::to(['/store/provider']) ?>"><?= Yii::t('store', "Frame Providers")?></a></li>
		    <li><a href="<?= Url::to(['/store/task']) ?>"><?= Yii::t('store', 'Tasks')?></a></li>
		    <li><a href="<?= Url::to(['/store/item/tasks']) ?>"><?= Yii::t('store', "Items' Associated Tasks")?></a></li>
		</ul>
		
	</div>
	<?php if(User::hasRole(['manager', 'admin', 'employee', 'compta'])): ?>
	<div class="col-lg-6">

		<h3>Comptabilité</h3>

		<div data-intro='Menu secondaire vers options de recherches et actions'>

			<ul>
			    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
			    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
			</ul>
			<ul>
			    <li><a href="<?= Url::to(['/order/document/bulk', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Bulk Bill')?></a></li>
		    	<li><a href="<?= Url::to(['/accnt']) ?>"><strong><?= Yii::t('store', 'Accounting')?></strong></a></li>
			</ul>
			<ul>
			    <li><a href="<?= Url::to(['/accnt/pdf', 'sort' => '-created_at']) ?>"><?= Yii::t('store', 'Documents to Print')?></a></li>
			</ul>

		</div>
		<?php if($viewstats): ?>
  	<div class="col-lg-6">

		<h3>Statistiques</h3>

			<ul>
		    	<li><a href="<?= Url::to(['/stats']) ?>"><?= Yii::t('store', 'Statistics')?></a></li>
			</ul>

		</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>
</div>

</div>
