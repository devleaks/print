<?php
?>
Line:
{
      GnrlID:               <?= $model->id ?>

      AnalID:               
      VATCode:              <?= $model->vat ?>

      Comment:              <?= $model->note ?>

      FlagDC:               C
      AmountCrcy:           <?= $model->price_htva ?>

      AmountCrcyDoc:        <?= $model->price_htva ?>

      AmountCrcyBase:       <?= $model->price_htva ?>

      AmountVATCrcyDoc:     <?= $model->price_htva * ($model->vat / 100) ?>

}
