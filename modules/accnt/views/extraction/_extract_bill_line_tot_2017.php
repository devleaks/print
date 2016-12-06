<?php
/*
DOCTYPE,DBKCODE,DBKTYPE,DOCNUMBER,DOCORDER,OPCODE,ACCOUNTGL,ACCOUNTRP,BOOKYEAR,PERIOD,DATE,DATEDOC,DUEDATE,COMMENT,COMMENTEXT,AMOUNT,AMOUNTEUR,VATBASE,VATCODE,CURRAMOUNT,CURRCODE,CUREURBASE,VATTAX,VATIMPUT,CURRATE,REMINDLEV,MATCHNO,OLDDATE,ISMATCHED,ISLOCKED,ISIMPORTED,ISPOSITIVE,ISTEMP,MEMOTYPE,ISDOC,DOCSTATUS,DICFROM,CODAKEY,WOW,QUANTITY,DISCDATE,DISCAMOUNT,DATESTAMP,TIMESTAMP,USERNAME

1      ,VENTES ,      2,  1602907,     001,      ,   400000,      107,       4,    06,2020061016,2020061016,20160610,,       ,      ,145.22   ,137    ,       ,          ,         ,         ,  8.22,        , ,      ,         ,       ,       ,         ,        ,          ,          ,      ,        ,     ,         ,
3,VENTES,2,1602907,002,,700100,107,4,06,2020061016,2020061016,20160610,107,,,-137,,,,,,,211200,,,,,,,,,,,,,,
3,VENTES,2,1602907,VAT,FIXED,451000,107,4,06,2020061016,2020061016,20160610,107,,,-8.22,137,211200,,,,,,,,,,,,,,,,,,,

1,VENTES,2,1602908,001,,400000,920,4,06,2020061016,2020061016,20160610,,,,91.9,86.7,,,,,5.2,,,,,,,,,,,,,,,
3,VENTES,2,1602908,002,,700100,920,4,06,2020061016,2020061016,20160610,920,,,-86.7,,,,,,,211200,,,,,,,,,,,,,,
3,VENTES,2,1602908,VAT,FIXED,451000,920,4,06,2020061016,2020061016,20160610,920,,,-5.2,86.7,211200,,,,,,,,,,,,,,,,,,,

1,VENTES,2,1602909,001,,400000,769,4,06,2020061016,2020061016,20160610,,,,2185.27,2048.33,,,,,136.94,,,,,,,,,,,,,,,
3,VENTES,2,1602909,002,,700100,769,4,06,2020061016,2020061016,20160610,769,,,-1954.73,,,,,,,211200,,,,,,,,,,,,,,
3,VENTES,2,1602909,003,,700300,769,4,06,2020061016,2020061016,20160610,769,,,-93.6,,,,,,,211400,,,,,,,,,,,,,,
3,VENTES,2,1602909,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-117.28,1954.73,211200,,,,,,,,,,,,,,,,,,,
3,VENTES,2,1602909,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-19.66,93.6,211400,,,,,,,,,,,,,,,,,,,

1,VENTES,2,1602910,001,,400000,769,4,06,2020061016,2020061016,20160610,,,,1861.40,1747.04,,,,,114.36,,,,,,,,,,,,,,,
3,VENTES,2,1602910,002,,700100,769,4,06,2020061016,2020061016,20160610,769,,,-1683.44,,,,,,,211200,,,,,,,,,,,,,,
3,VENTES,2,1602910,003,,700300,769,4,06,2020061016,2020061016,20160610,769,,,-63.6,,,,,,,211400,,,,,,,,,,,,,,
3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-101.01,1683.44,211200,,,,,,,,,,,,,,,,,,,
3,VENTES,2,1602910,VAT,FIXED,451000,769,4,06,2020061016,2020061016,20160610,769,,,-13.35,63.6,211400,,,,,,,,,,,,,,,,,,,

1,VENTES,2,1602911,001,,400000,450,4,06,2020061016,2020061016,20160610,,,,390.08,368,,,,,22.08,,,,,,,,,,,,,,,
3,VENTES,2,1602911,002,,700100,450,4,06,2020061016,2020061016,20160610,450,,,-368,,,,,,,211200,,,,,,,,,,,,,,
3,VENTES,2,1602911,VAT,FIXED,451000,450,4,06,2020061016,2020061016,20160610,450,,,-22.08,368,211200,,,,,,,,,,,,,,,,,,,

*/
$vat = $order->price_tvac - $order->price_htva;
$no_vat = ($order->vat_bool || $vat == 0);

$record['DOCTYPE'] = 1;
$record['DBKCODE'] = 'VENTE';
$record['DBKTYPE'] = 2;
$record['DOCNUMBER'] = $order->name;
$record['DOCORDER'] = '001';
$record['OPCODE'] = '';
$record['ACCOUNTGL'] = '400000';
$record['ACCOUNTRP'] = '';
$record['BOOKYEAR'] = substr($order->name, 0, 4);
$record['PERIOD'] = date('m', strtotime($order->created_at));
$record['DATE'] = date('m', strtotime($order->due_date));
$record['DATEDOC'] = date('Ymd', strtotime($order->created_at));
$record['DUEDATE'] = date('Ymd', strtotime("+ 1 month", strtotime($order->created_at)));
$record['COMMENT'] = '';
$record['COMMENTEXT'] = '';
$record['AMOUNT'] = '';
$record['AMOUNTEUR'] = ($no_vat ? $order->price_htva : $order->price_tvac);
$record['VATBASE'] = '';
$record['VATCODE'] = '';
$record['CURRAMOUNT'] = '';
$record['CURRCODE'] = '';
$record['CUREURBASE'] = '';
$record['VATTAX'] = '';
$record['VATIMPUT'] = ($no_vat ? '' : $order->price_tvac - $order->price_htva);
$record['CURRATE'] = '';
$record['REMINDLEV'] = '';
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
?>