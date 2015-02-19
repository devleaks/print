<?php

use yii\helpers\Url;

$this->title = Yii::t('store', 'Renforts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="accnt-default-index">
    <h1><?= Yii::t('store', 'Renforts') ?></h1>

    <p>
    </p>

	<ul>
	    <li><a href="<?= Url::to(['/work/renfort/to-cut']) ?>"><?= Yii::t('store', 'Renforts to prepare')?></a></li>
	    <li><a href="<?= Url::to(['/work/renfort/print-cuts']) ?>"><?= Yii::t('store', 'Renforts to cut')?></a></li>
	    <li><a href="<?= Url::to(['/work/master/']) ?>"><?= Yii::t('store', 'Masters')?></a></li>
	</ul>



</div>