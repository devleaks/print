<?php

use yii\helpers\Url;

$this->title = Yii::t('store', 'Accounting');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="accnt-default-index">
    <h1><?= Yii::t('store', 'Accounting') ?></h1>

    <p>
    </p>

	<ul>
	    <li><a href="<?= Url::to(['/accnt/default/nopopsyclinum']) ?>"><?= Yii::t('store', 'Client without Popsy Account Identifier')?></a>
		 	(<?= $nopopsyclinum ?>)</li>
	</ul>



</div>