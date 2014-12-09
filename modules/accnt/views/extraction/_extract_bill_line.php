<?php
?>
Line:
{
      GnrlID:               <?= $model->id ?>

      AnalID:               
      VATCode:              <?= $model->vat ?>

      Comment:              <?= $model->note ?>

      FlagDC:               C
      AmountCrcy:           <?= number_format($model->price_htva, 2, ',', '') ?>

      AmountCrcyDoc:        <?= number_format($model->price_htva, 2, ',', '') ?>

      AmountCrcyBase:       <?= number_format($model->price_htva, 2, ',', '') ?>

      AmountVATCrcyDoc:     <?= number_format($model->price_htva * ($model->vat / 100), 2, ',', '') ?>

}
