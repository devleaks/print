<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>

Customer:<?= $model->reference_interne ?>
{
      FirstName:            <?= $model->prenom ?>

      LastName:             <?= $model->nom ?>

      Address:              <?= $model->adresse ?>

      ZipCode:              <?= $model->code_postal ?>

      City:                 <?= $model->localite ?>

      Language:             0
      Country:              BE
      Title:                <?= $model->titre ?>

      Company:              <?= $model->autre_nom ?>

      PhoneBusiness:        <?= $model->bureau ?>

      PhoneFax:             <?= $model->fax_bureau ?>

      PhoneHome:            <?= $model->domicile ?>

      PhoneMobile:          <?= $model->gsm ?>

      Email:                <?= $model->email ?>

      Url:                  <?= $model->site_web ?>

      VatType:              0
      VatNum:               <?= str_replace(' ', '', str_replace('BE', '',$model->numero_tva)) ?>

      VatFormat:            <?= $model->numero_tva ?
									strpos('BE', $model->numero_tva) > -1 ? 'BE' : substr($model->numero_tva, 0, 2) //pas tout à fait correct...
									:
									'' ?>
									
}
