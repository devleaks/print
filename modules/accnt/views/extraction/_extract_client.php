<?php
use app\models\Parameter;
use app\components\VATValidator;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

// @todo remove quotes from names strings


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

?>

Customer:<?= $model->comptabilite == '' ? 'UNKNOWN' : $model->comptabilite ?>

{
      FirstName:            <?= substr($model->prenom, 0, 32) ?>

      LastName:             <?= substr($model->nom, 0, 32) ?>

      Address:              <?= $model->adresse ?>

      ZipCode:              <?= $model->code_postal ?>

      City:                 <?= $model->localite ?>

      Language:             <?= Parameter::getIntegerValue('langue', Parameter::getName('langue', $model->lang, 0)) ?>

      Country:              <?= Parameter::getName('pays', $model->pays) ?>

      Title:                <?= $model->titre ?>

      Company:              <?= substr($model->autre_nom, 0, 32) ?>

      PhoneBusiness:        <?= $model->bureau ?>

      PhoneFax:             <?= $model->fax_bureau ?>

      PhoneHome:            <?= $model->domicile ?>

      PhoneMobile:          <?= $model->gsm ?>

      Email:                <?= $model->email ?>

      Url:                  <?= $model->site_web ?>

      VatType:              <?= $has_vat ? 0 : 7 ?>

      VatNum:               <?= $has_vat ? ($vat_number ? $vat_number : $vat_clean) : 'NA' ?>

<?php if($has_vat): ?>
      VatFormat:            <?= $country_code ?>
<?php endif; ?>
									
}
