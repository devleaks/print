<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Update Application');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Update Application'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$UPDATE = Yii::getAlias('@app')."/runtime/etc/update.sh $version";
?>
<div class="admin-default-index">
	
    <h1><?= $this->title ?></h1>

<pre>
	<?= 'Migrating to version '.$version ; ?>
</pre>

</div>
