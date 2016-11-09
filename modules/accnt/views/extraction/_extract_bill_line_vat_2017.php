<?php

$vat = $order->price_tvac - $order->price_htva;
if($order->vat_bool || $vat == 0) // no VAT line
	return;
	
// Yii::$app->session->addFlash('info', Yii::t('store', '{0} OK.', $order->name));
?>
1,VENTES,2,<?= $order->name ?>,FIXED,1010101,<?= $vat ?>,

