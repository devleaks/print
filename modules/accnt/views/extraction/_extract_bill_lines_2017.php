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

CREATE ALGORITHM=UNDEFINED DEFINER=`yii2print`@`%` SQL SECURITY DEFINER VIEW `document_account_line`
AS SELECT
   `dl`.`document_id` AS `document_id`,
   `i`.`comptabilite` AS `comptabilite`,
   `dl`.`vat` AS `taux_de_tva`,sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) AS `total_price_htva`,sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`)) AS `total_extra_htva`,(sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) AS `total_htva`,if(isnull(`dl`.`vat`),0,sum(round(((if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`) + if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) * (`dl`.`vat` / 100)),2))) AS `total_vat`,if(isnull(`dl`.`vat`),0,round(((sum(if(isnull(`dl`.`price_htva`),0,`dl`.`price_htva`)) + sum(if(isnull(`dl`.`extra_htva`),0,`dl`.`extra_htva`))) * (`dl`.`vat` / 100)),2)) AS `total_vat_ctrl`
FROM (`document_line` `dl` join `item` `i`) where (`dl`.`item_id` = `i`.`id`) group by `dl`.`document_id`,`i`.`comptabilite`,`dl`.`vat`;

*/
?>
<?php
	$warning_threshold = 0.0099; // about 0.01€

	$total = 0;
	$total_vat = 0;
	$count = 1;
	$vat_lines = [];
	$vat_amount_lines = [];

	// debit line from customer
	echo $this->render('_extract_bill_line_tot_2017' , ['order' => $order]);

	$vat = $order->price_tvac - $order->price_htva;
	$no_vat = ($order->vat_bool || $vat == 0);

	// credit lines into accounts one, per (comptabilite,taux_de_tva)
	foreach($order->getAccountLines()->orderBy('comptabilite,taux_de_tva')->each() as $al) {
		$al->position = ++$count;
		echo $this->render('_extract_bill_line_2017' , ['model' => $al, 'order' => $order]);
		$total += ($al->total_price_htva + $al->total_extra_htva);
		if($no_vat) {
			$vat_lines['0.00'] = 0;
			$vat_amount_lines['0.00'] = $order->price_htva;
		} else {
			$vat_rate = number_format($al->taux_de_tva, 2); // normalize VAT rate format: 21.50, 7.60, etc.
			if(isset($vat_lines[$vat_rate])) {
				$vat_lines[$vat_rate] += $al->total_vat;
				$vat_amount_lines[$vat_rate] += ($al->total_price_htva + $al->total_extra_htva);
			} else {
				$vat_lines[$vat_rate] = $al->total_vat;
				$vat_amount_lines[$vat_rate] = ($al->total_price_htva + $al->total_extra_htva);
			}
			$total_vat += $al->total_vat;
		}
	}

	// credit lines into vat accounts, one per (comptabilite,taux_de_tva)
	echo $this->render('_extract_bill_line_vat_2017' , ['vat_lines' => $vat_lines, 'vat_amount_lines' => $vat_amount_lines, 'order' => $order]);

	// control roundings. Add a line to adjust for rounding if necessary
	$ctrl = round(floatval($total - $order->price_htva), 3);
	if($ctrl > $warning_threshold) {
		Yii::$app->session->addFlash('warning', Yii::t('store', 'Checksum error: {0} HTVA differs: {1} vs. {2}.',
			[$order->name, $total, $order->price_htva]));
		//@todo ADD LINE FOR DIFFERENCE
	}

	$ctrl = round(floatval($total_vat - $order->price_tvac + $order->price_htva), 3);
	if($ctrl > $warning_threshold) {
		Yii::$app->session->addFlash('warning', Yii::t('store', 'Checksum error: {0} VAT differs: {1} vs. {2} ({3}).',
			[$order->name, $total_vat, ($order->price_tvac - $order->price_htva), $ctrl]));
	}
?>
