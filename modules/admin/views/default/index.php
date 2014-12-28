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
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/sequence-data/"><?= Yii::t('store', 'Manage Sequence Numbers')?></a></li>
	</ul>

</div>
