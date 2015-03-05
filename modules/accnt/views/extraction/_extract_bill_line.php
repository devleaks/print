<?php

$amount = abs($model->price_htva + $model->extra_htva);

?>
Line:
{
      GnrlID:               <?= /*$model->item->comptabilite == '' ? '??????' :*/ $model->item->comptabilite ?>

      AnalID:               
      VATCode:              <?= $order->vat_bool ? 0 : $model->vat ?>

      Comment:
      FlagDC:               <?= $model->price_htva < 0 ? 'D' : 'C' ?>

      AmountCrcy:           <?= $amount ?>

      AmountCrcyDoc:        <?= $amount ?>

      AmountCrcyBase:       <?= $amount ?>

      AmountVATCrcyDoc:     <?= $order->vat_bool ? 0 : round($amount * ($model->vat / 100), 2) ?>

}
