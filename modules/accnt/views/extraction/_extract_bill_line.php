<?php
use app\models\Item;

$rebate_item = Item::findOne(['reference' => Item::TYPE_REBATE]);
$factor = ($model->item_id == $rebate_item->id) ? -1 : 1;

$amount = $factor * abs($model->price_htva + $model->extra_htva);

?>
Line:
{
      GnrlID:               <?= /*$model->item->comptabilite == '' ? '??????' :*/ $model->item->comptabilite ?>

      AnalID:               
      VATCode:              <?= $order->vat_bool ? 0 : number_format($model->vat, 0) ?>

      Comment:
      FlagDC:               <?= $model->price_htva < 0 ? 'D' : 'C' ?>

      AmountCrcy:           <?= $amount ?>

      AmountCrcyDoc:        <?= $amount ?>

      AmountCrcyBase:       <?= $amount ?>

      AmountVATCrcyDoc:     <?= $order->vat_bool ? 0 : round($amount * ($model->vat / 100), 2) ?>

}
