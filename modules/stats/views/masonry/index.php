<?php
use yii\helpers\Url;

$this->title = Yii::t('store', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stats-default-index">
    <h1><?= $this->title ?></h1>

    <p>
    </p>

	<ul>
	    <li><a href="<?= Yii::$app->homeUrl ?>stats/masonry/"><?= Yii::t('store', 'Masonry')?></a></li>
	</ul>

</div>
