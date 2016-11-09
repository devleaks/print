<?php
use app\models\Client;
use app\models\Parameter;
use app\components\VATValidator;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$COUNTRY_CODES = ['AT','BE','BR','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','EL','HU','IS','IE','IT','LV','LT','LU','MT','NL','NO','PL','PT','RO','RU','RS','SK','SI','ZA','ES','SE','CH','GB','VE'];

$has_vat = $model->assujetti_tva ? false : ($model->numero_tva && !in_array(strtolower($model->numero_tva), ['non assujetti', 'inconnu']));

$vat_clean = preg_replace('/[^a-zA-Z0-9]/', '', $model->numero_tva);

$country_code = $has_vat ? substr($vat_clean, 0, 2) : null;
$vat_number = null;

if(in_array($country_code, $COUNTRY_CODES)) { // if first two characters are a valid country code
	$vat_number = substr($vat_clean, 2);
} else {
	$country_code = 'BE'; // country code is not valid, defaults to Belgium. VAT number is probably a simple VAT number without country prefix.
}
/*
NUMBER,TYPE,NAME1,NAME2,CIVNAME1,CIVNAME2,ADRESS1,ADRESS2,VATCAT,COUNTRY,VATNUMBER,PAYCODE,TELNUMBER,FAXNUMBER,BNKACCNT,ZIPCODE,CITY,DEFLTPOST,LANG,CATEGORY,CENTRAL,VATCODE,CURRENCY,LASTREMLEV,LASTREMDAT,TOTDEB1,TOTCRE1,TOTDEBTMP1,TOTCRETMP1,TOTDEB2,TOTCRE2,TOTDEBTMP2,TOTCRETMP2,ISLOCKED,MEMOTYPE,ISDOC,F28150,WBMODIFIED,WOW,DISCPRCT,DISCTIME,EMAIL,REG28150,PAYLOCKED,TOAPPROVE,EREMINDERS,IBANAUTO,BICAUTO,STATUS281,SECNAME281,FIRNAME281,NUM281,ZON1,INVISIBLE,INTRASTAT,DATESTAMP,TIMESTAMP,USERNAME

0001,1,AUX TROIS CANARDS,,,,51 ROUTE DE LA MARACHE,,1,BE,0477.574.154,,02-633.21.81,0475-934.382,,BE-1380,Bruxelles,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
0002,1,ALTROMONDO,,,,406 CHAUSSEE DE LOUVAIN,,1,BE,0860.433.352,,010-243.595,,,BE-1300,Wavre,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
0003,1,ARCOMA,,,,3 GRANDE PLACE,,1,BE,0465.657.903,,067-211.241,067-841.704,,BE-1400,Nivelles,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
0004,1,APOSTROPHE,,,,195 BRUSSELSTRAAT,,1,BE,0443.895.853,,02-466.10.15,,,BE-1702,Groot-Bijgaarden,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
0005,1,BEMANOS,,,,23-27 SQUARE DE L'AVIATION,,1,BE,0861.983.867,,02-520.66.65,02-520.67.67,,BE-1070,Bruxelles Anderlecht,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
0006,1,NE PLUS UTI A LA VRAIE BELLE EPOQUE,,,,3-5 RUE DU PROGRES,,1,BE,0822.488.239,,02-201.90.79,,,BE-1210,St-Josse-ten-Noode,,,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,

*/
?>
<?= $model->id ?>,1,<?= Client::sanitizePopsy($model->nom.' '.$model->prenom, 40) ?>,<?= Client::sanitizePopsy($model->autre_nom, 40) ?>,<?= $model->titre ?>,,<?= ($has_vat ? 1 : 3) ?>,<?= Parameter::getName('pays', $model->pays) ?>,<?= $vat_clean ?>,,<?= $model->bureau ?>,<?= $model->fax_bureau ?>,,<?= $model->code_postal ?>,<?= $model->localite ?>,,<?= strtoupper($model->lang) ?>,,,,,,,,,,,,,,,,,,0,T,,,,NULL,,,
