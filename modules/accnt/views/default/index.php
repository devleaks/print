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
	    <li><a href="<?= Url::to(['/order/document/credits']) ?>"><?= Yii::t('store', 'Credit Notes')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/bill']) ?>"><?= Yii::t('store', 'Unpaid Bills')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/bill/boms']) ?>"><?= Yii::t('store', 'Bill all BOMs')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/extraction']) ?>"><?= Yii::t('store', 'Monthly Extraction')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/pdf']) ?>"><?= Yii::t('store', 'Documents')?></a></li>
	    <li><a href="<?= Url::to(['/store/client', 'sort' => '-updated_at']) ?>"><?= Yii::t('store', 'Clients')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/cash']) ?>"><?= Yii::t('store', 'Cash')?></a></li>
	    <li><a href="<?= Url::to(['/accnt/default/control']) ?>"><?= Yii::t('store', 'Checks')?></a></li>
	</ul>



</div>