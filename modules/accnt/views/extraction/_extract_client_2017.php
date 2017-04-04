<?php
use app\models\Client;
use app\models\Parameter;
use app\components\VATValidator;
use app\components\EuVATValidator;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$COUNTRY_CODES = ['AT','BE','BR','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','EL','HU','IS','IE','IT','LV','LT','LU','MT','NL','NO','PL','PT','RO','RU','RS','SK','SI','ZA','ES','SE','CH','GB','VE'];

$has_vat = $model->assujetti_tva ? false : ($model->numero_tva && !in_array(strtolower($model->numero_tva), ['non assujetti', 'inconnu']));

$vat_clean = preg_replace('/[^a-zA-Z0-9]/', '', $model->numero_tva);

$vat_number = null;
$country_code = $has_vat ? substr($vat_clean, 0, 2) : null;
if(in_array($country_code, $COUNTRY_CODES)) { // if first two characters are a valid country code
	$vat_number = substr($vat_clean, 2);
} else {
	$country_code = 'BE'; // country code is not valid, defaults to Belgium. VAT number is probably a simple VAT number without country prefix.
	$vat_number = $vat_clean;
}

if($country_code == 'BE' && $has_vat) { // format XXXX.XXX.XXX for Belgium
	if(! ($model->numero_tva_norm)) {
		$model->numero_tva_norm = EuVATValidator::cleanVAT($this->numero_tva);
		Yii::$app->session->addFlash('warning', Yii::t('store', 'VAT Number '.$model->numero_tva.'for client '.$model->name.' has not been validated.'));
	}
	$vat_clean2 = substr($vat_number, 2, 4).'.'.substr($vat_number, 6, 3).'.'.substr($vat_number, 9, 3);
	$vat_clean  = substr($model->numero_tva_norm, 2, 4).'.'.substr($model->numero_tva_norm, 6, 3).'.'.substr($model->numero_tva_norm, 9, 3);
	Yii::trace($vat_clean.' vs '.$vat_clean2, 'extraction::_extract_client_2017');
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

$record['NUMBER'] = $model->comptabilite;
$record['TYPE'] = 1;
$record['NAME1'] = Client::sanitizeWinbooks($model->nom.' '.$model->prenom, 40);
$record['NAME2'] = Client::sanitizeWinbooks($model->autre_nom, 40);
$record['CIVNAME1'] = $model->titre;
$record['CIVNAME2'] = '';
$record['ADRESS1'] = Client::sanitizeWinbooks($model->adresse, 40);
$record['ADRESS2'] = '';
$record['VATCAT'] = ($has_vat ? 1 : 3);
$record['COUNTRY'] = Parameter::getName('pays', $model->pays);
$record['VATNUMBER'] = ($has_vat ? $vat_clean : '');
$record['PAYCODE'] = '';
$record['TELNUMBER'] = $model->bureau;
$record['FAXNUMBER'] = $model->fax_bureau;
$record['BNKACCNT'] = '';
$record['ZIPCODE'] = $model->code_postal;
$record['CITY'] = Client::sanitizeWinbooks($model->localite);
$record['DEFLTPOST'] = '';
$record['LANG'] = strtoupper($model->lang);
$record['CATEGORY'] = '';
$record['CENTRAL'] = '';
$record['VATCODE'] = '';
$record['CURRENCY'] = '';
$record['LASTREMLEV'] = '';
$record['LASTREMDAT'] = '';
$record['TOTDEB1'] = '';
$record['TOTCRE1'] = '';
$record['TOTDEBTMP1'] = '';
$record['TOTCRETMP1'] = '';
$record['TOTDEB2'] = '';
$record['TOTCRE2'] = '';
$record['TOTDEBTMP2'] = '';
$record['TOTCRETMP2'] = '';
$record['ISLOCKED'] = '';
$record['MEMOTYPE'] = '';
$record['ISDOC'] = '';
$record['F28150'] = '';
$record['WBMODIFIED'] = '';
$record['WOW'] = '';
$record['DISCPRCT'] = '';
$record['DISCTIME'] = '';
$record['EMAIL'] = '';
$record['REG28150'] = '';
$record['PAYLOCKED'] = '';
$record['TOAPPROVE'] = '';
$record['EREMINDERS'] = '';
$record['IBANAUTO'] = '';
$record['BICAUTO'] = '';
$record['STATUS281'] = '';
$record['SECNAME281'] = '0';
$record['FIRNAME281'] = 'T';
$record['NUM281'] = '';
$record['ZON1'] = '';
$record['INVISIBLE'] = '';
$record['INTRASTAT'] = 'NULL';
$record['DATESTAMP'] = '';
$record['TIMESTAMP'] = '';
$record['USERNAME'] = '';

// $rec = array_map(Client::sanitizeWinbooks, $record);

echo implode(',', $record)."\r\n"

?>
