<?php

/*
DOCTYPE,DBKCODE,DBKTYPE,DOCNUMBER,DOCORDER,OPCODE,ACCOUNTGL,ACCOUNTRP,BOOKYEAR,PERIOD,DATE,DATEDOC,DUEDATE,COMMENT,COMMENTEXT,AMOUNT,AMOUNTEUR,VATBASE,VATCODE,CURRAMOUNT,CURRCODE,CUREURBASE,VATTAX,VATIMPUT,CURRATE,REMINDLEV,MATCHNO,OLDDATE,ISMATCHED,ISLOCKED,ISIMPORTED,ISPOSITIVE,ISTEMP,MEMOTYPE,ISDOC,DOCSTATUS,DICFROM,CODAKEY,WOW,QUANTITY,DISCDATE,DISCAMOUNT,DATESTAMP,TIMESTAMP,USERNAME

3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-101.01,1683.44,211200,,,,,,,,,,,,,,,,,,,
3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-13.35,63.6,211400,,,,,,,,,,,,,,,,,,,

*/
$vat = $order->price_tvac - $order->price_htva;
if($order->vat_bool || $vat == 0) // no VAT line
	return;
	
// Yii::$app->session->addFlash('info', Yii::t('store', '{0} OK.', $order->name));
foreach($vat_lines as $vat_rate => $vat_amount) {
	echo '3,VENTES,2,'.$order->name.',VAT,FIXED,451000,769,4,'.date('m', strtotime($order->created_at)).','.date('m', strtotime($order->due_date)).','.date('Ymd', strtotime($order->created_at)).','.date('Ymd', strtotime("+ 1 month", strtotime($order->created_at))).',769,,,'.$vat_amount.','.$vat_rate.',211400,,,,,,,,,,,,,,,,,,,';
}
?>

