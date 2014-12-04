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
	    <li><a href="<?= Url::to(['/accnt/payment/index-by-type']) ?>"><?= Yii::t('store', 'Daily Summary')?></a></li>
	</ul>
	<ul>
	    <li><a href="<?= Url::to(['/accnt/bill']) ?>"><?= Yii::t('store', 'Unpaid Bills')?></a></li>
	</ul>
	<ul>
	    <li><a href="<?= Url::to(['/accnt/extraction']) ?>"><?= Yii::t('store', 'Monthly Extraction')?></a></li>
	</ul>

</div>