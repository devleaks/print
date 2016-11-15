<?php
/* The view document_account_line groups order lines by accounting number, found in item.

create or replace view document_account_line
as
select dl.document_id as document_id,
       i.comptabilite as comptabilite,
       dl.vat as taux_de_tva,
       if(isnull(dl.vat), 0, sum( round(( if(isnull(dl.extra_htva),0,dl.extra_htva) + if(isnull(dl.price_htva),0,dl.price_htva) ) * (dl.vat / 100), 2))) as total_vat,
	   sum(if(isnull(dl.price_htva),0,dl.price_htva)) as total_price_htva,
	   sum(if(isnull(dl.extra_htva),0,dl.extra_htva)) as total_extra_htva,
	   sum(if(isnull(dl.price_htva),0,dl.price_htva)) + sum(if(isnull(dl.extra_htva),0,dl.extra_htva)) as total_htva,
       if( isnull(dl.vat), 0, round( (sum(if(isnull(dl.price_htva),0,dl.price_htva)) + sum(if(isnull(dl.extra_htva),0,dl.extra_htva))) * (dl.vat / 100), 2) ) as total_vat_ctrl
  from document_line dl,
       item i
 where dl.item_id = i.id
 group by dl.document_id,
          i.comptabilite,
          dl.vat

*/
?>
<?php
	$total = 0;
	$total_vat = 0;
	$count = 0;
	foreach($order->getAccountLines()->orderBy('comptabilite,taux_de_tva')->each() as $al) {
//	foreach($model->each() as $al) {
		$al->position = ++$count;
		echo $this->render('_extract_bill_line_2017' , ['model' => $al, 'order' => $order]);
		$total += ($al->total_price_htva + $al->total_extra_htva);
		$total_vat += $al->total_vat;
	}
	echo $this->render('_extract_bill_line_vat_2017' , ['order' => $order]);
	if($total != $order->price_htva) {
		Yii::$app->session->addFlash('warning', Yii::t('store', 'Checksum error: {0} HTVA differs: {1} vs. {2}.', [$order->name, $total, $order->price_htva]));
	}
	$ctrl = round(floatval($total_vat - $order->price_tvac + $order->price_htva), 3);
	if($ctrl > 0.0099) {
		Yii::$app->session->addFlash('warning', Yii::t('store', 'Checksum error: {0} VAT differs: {1} vs. {2} ({3}).', [$order->name, $total_vat, ($order->price_tvac - $order->price_htva), $ctrl]));
	}
?>