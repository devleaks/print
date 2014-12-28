<?php
$amount = abs($model->price_htva);
?>
Line:
{
      GnrlID:               <?= $model->item->comptabilite == '' ? '??????' : $model->item->comptabilite ?>

      AnalID:               
      VATCode:              <?= $model->vat ?>

      Comment:              <?= $model->note ?>

      FlagDC:               <?= $model->price_htva < 0 ? 'D' : 'C' ?>

      AmountCrcy:           <?= $amount ?>

      AmountCrcyDoc:        <?= $amount ?>

      AmountCrcyBase:       <?= $amount ?>

      AmountVATCrcyDoc:     <?= round($amount * ($model->vat / 100), 2) ?>

}
