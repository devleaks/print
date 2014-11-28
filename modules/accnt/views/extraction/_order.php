<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>

Sales:
{
Header:
{
      JrnlID:               FV1

      DocType:              1

      DocNumber:            <?= str_replace('-', '', $model->name) ?>

      CustID:               <?= $model->client->reference_interne ?>

      Comment:              <?= $model->note ?>

      PeriodID:             10

      DateDoc:              <?= $model->created_at ?>

      DateDue:              <?= $model->due_date ?>

      Piece:                <?= str_replace('-', '', $model->name) ?>

      CrcyDoc:              EUR

      AmountCrcyDoc:        <?= $model->price_tvac ?>

      AmountCrcyBase:       <?= $model->price_tvac ?>

}
<?= $this->render('_order_lines', ['model' => $model->getOrderLines()]) ?>
}
