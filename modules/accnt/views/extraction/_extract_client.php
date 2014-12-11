<?php
use app\models\Parameter;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>

Customer:<?= $model->comptabilite == '' ? 'UNKNOWN' : $model->comptabilite ?>
{
      FirstName:            <?= $model->prenom ?>

      LastName:             <?= $model->nom ?>

      Address:              <?= $model->adresse ?>

      ZipCode:              <?= $model->code_postal ?>

      City:                 <?= $model->localite ?>

      Language:             <?= Parameter::getIntegerValue('langue', Parameter::getName('langue', $model->lang, 0)) ?>

      Country:              <?= Parameter::getName('pays', $model->pays) ?>

      Title:                <?= $model->titre ?>

      Company:              <?= $model->autre_nom ?>

      PhoneBusiness:        <?= $model->bureau ?>

      PhoneFax:             <?= $model->fax_bureau ?>

      PhoneHome:            <?= $model->domicile ?>

      PhoneMobile:          <?= $model->gsm ?>

      Email:                <?= $model->email ?>

      Url:                  <?= $model->site_web ?>

      VatType:              0
      VatNum:               <?= trim(str_replace(' ', '', str_replace('BE', '',$model->numero_tva))) ?>

      VatFormat:            <?= $model->numero_tva ?
									strpos('BE', $model->numero_tva) > -1 ? 'BE' : substr($model->numero_tva, 0, 2) //pas tout à fait correct...
									:
									'' ?>
									
}
