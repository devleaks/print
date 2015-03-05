<?php
?>
|     Popsy file

CreateKeyCustomer:Y
IgnoreAnalClosed:Y
DossierSelect:001
AcctingSelect:<?= date('y') ?>

<?= $this->render('_extract_clients' , ['model' => $clients]) ?>

<?= $this->render('_extract_bills' , ['model' => $models]) ?>
