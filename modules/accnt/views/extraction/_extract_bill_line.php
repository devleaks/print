<?php
use app\models\Item;

$rebate_item = Item::findOne(['reference' => Item::TYPE_REBATE]);
$amount = $model->price_htva + $model->extra_htva;
$abs_amount = abs($amount);
$factor = ($model->item_id == $rebate_item->id) ? -1 : ($amount < 0 ? -1 : 1);

?>
Line:
{
      GnrlID:               <?= $model->item->comptabilite ?>

      AnalID:               
      VATCode:              <?= $order->vat_bool ? 0 : number_format($model->vat, 0) ?>

      Comment:
      FlagDC:               <?= $factor < 0 ? 'D' : 'C' ?>

      AmountCrcy:           <?= $abs_amount ?>

      AmountCrcyDoc:        <?= $abs_amount ?>

      AmountCrcyBase:       <?= $abs_amount ?>

      AmountVATCrcyDoc:     <?= $order->vat_bool ? 0 : round($abs_amount * ($model->vat / 100), 2) ?>

}
