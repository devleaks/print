<?php
/*
create or replace view document_account_line
as
select dl.document_id as document_id,
       i.comptabilite as comptabilite,
       if(isnull(sum(dl.vat)), 0, sum(dl.vat)) as vat,
	   sum(dl.price_htva) as total_price_htva,
       if(isnull(sum(dl.extra_htva)), 0, sum(dl.extra_htva)) as total_extra_htva
  from document_line dl,
       item i
 where dl.item_id = i.id
 group by dl.document_id,i.comptabilite, dl.vat
*/
?>
<?php
	$total = 0;
	$count = 0;
	foreach($order->getAccountLines()->orderBy('comptabilite,vat')->each() as $ol) {
//	foreach($model->each() as $ol) {
		$ol->position = ++$count;
		echo $this->render('_extract_bill_line_2017' , ['model' => $ol, 'order' => $order]);
		$total += ($ol->total_price_htva + $ol->total_extra_htva);
	}
	echo $this->render('_extract_bill_line_vat_2017' , ['order' => $order]);
	if($total != $order->price_htva) {
		Yii::$app->session->addFlash('warning', Yii::t('store', 'Checksum error: {0} HTVA differs: {1} vs. {2}.', [$order->name, $total, $order->price_htva]));
	}
?>