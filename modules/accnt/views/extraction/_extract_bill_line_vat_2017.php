<?php

/*
DOCTYPE,DBKCODE,DBKTYPE,DOCNUMBER,DOCORDER,OPCODE,ACCOUNTGL,ACCOUNTRP,BOOKYEAR,PERIOD,DATE,DATEDOC,DUEDATE,COMMENT,COMMENTEXT,AMOUNT,AMOUNTEUR,VATBASE,VATCODE,CURRAMOUNT,CURRCODE,CUREURBASE,VATTAX,VATIMPUT,CURRATE,REMINDLEV,MATCHNO,OLDDATE,ISMATCHED,ISLOCKED,ISIMPORTED,ISPOSITIVE,ISTEMP,MEMOTYPE,ISDOC,DOCSTATUS,DICFROM,CODAKEY,WOW,QUANTITY,DISCDATE,DISCAMOUNT,DATESTAMP,TIMESTAMP,USERNAME

3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-101.01,1683.44,211200,,,,,,,,,,,,,,,,,,,
3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-13.35,63.6,211400,,,,,,,,,,,,,,,,,,,

*/
$vat = $order->price_tvac - $order->price_htva;
if($order->vat_bool || $vat == 0) // no VAT line
	return;
	
$VAT_ACCOUNT_NUMBERS = [];
$VAT_ACCOUNT_NUMBERS['21.0'] = '400210';
$VAT_ACCOUNT_NUMBERS['6.0'] = '400060';
	
// Yii::$app->session->addFlash('info', Yii::t('store', '{0} OK.', $order->name));
foreach($vat_lines as $vat_rate => $vat_amount) {
	
	$record['DOCTYPE'] = 3;
	$record['DBKCODE'] = 'VENTE';
	$record['DBKTYPE'] = 2;
	$record['DOCNUMBER'] = $order->name;
	$record['DOCORDER'] = 'VAT';
	$record['OPCODE'] = 'FIXED';
	$record['ACCOUNTGL'] = '451000'; // $VAT_ACCOUNT_NUMBERS[$vat_rate];
	$record['ACCOUNTRP'] = '';
	$record['BOOKYEAR'] = substr($order->name, 0, 4);
	$record['PERIOD'] = date('m', strtotime($order->created_at));
	$record['DATE'] = date('m', strtotime($order->due_date));
	$record['DATEDOC'] = date('Ymd', strtotime($order->created_at));
	$record['DUEDATE'] = date('Ymd', strtotime("+ 1 month", strtotime($order->created_at)));
	$record['COMMENT'] = '';
	$record['COMMENTEXT'] = '';
	$record['AMOUNT'] = '';
	$record['AMOUNTEUR'] = '';
	$record['VATBASE'] = '';
	$record['VATCODE'] = '';
	$record['CURRAMOUNT'] = '';
	$record['CURRCODE'] = '';
	$record['CUREURBASE'] = '';
	$record['VATTAX'] = '';
	$record['VATIMPUT'] = $vat_amount;
	$record['CURRATE'] = $vat_rate;
	$record['REMINDLEV'] = '211400'; // $VAT_ACCOUNT_NUMBERS[$vat_rate];
	$record['MATCHNO'] = '';
	$record['OLDDATE'] = '';
	$record['ISMATCHED'] = '';
	$record['ISLOCKED'] = '';
	$record['ISIMPORTED'] = '';
	$record['ISPOSITIVE'] = '';
	$record['ISTEMP'] = '';
	$record['MEMOTYPE'] = '';
	$record['ISDOC'] = '';
	$record['DOCSTATUS'] = '';
	$record['DICFROM'] = '';
	$record['CODAKEY'] = '';
	$record['WOW'] = '';
	$record['QUANTITY'] = '';
	$record['DISCDATE'] = '';
	$record['DISCAMOUNT'] = '';
	$record['DATESTAMP'] = '';
	$record['TIMESTAMP'] = '';
	$record['USERNAME'] = '';

	echo implode(',', $record)."\r\n";
}
?>