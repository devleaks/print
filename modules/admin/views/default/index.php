<div class="admin-default-index">
    <h1><?= Yii::t('store', 'Administration') ?></h1>

    <p>
    </p>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>user/admin/"><?= Yii::t('store', 'User Accounts')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/parameter/"><?= Yii::t('store', 'Parameters')?></a></li>
	    <li><a href="<?= Yii::$app->homeUrl ?>admin/backup/"><?= Yii::t('store', 'Database Backup')?></a></li>
	</ul>

</div>
