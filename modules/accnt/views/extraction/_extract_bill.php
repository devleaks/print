<?php
use app\models\Document;
$amount = abs($model->price_tvac);
?>

Sales:
{
Header:
{
      JrnlID:               <?= $model->document_type == Document::TYPE_BILL ? 'FV1' : ($model->document_type == Document::TYPE_CREDIT ? 'NV1' : '???') ?>

      DocType:              <?= $model->document_type == Document::TYPE_BILL ? 1 : ($model->document_type == Document::TYPE_CREDIT ? 2 : 0) ?>

      DocNumber:            <?= str_replace('-', '', $model->name) /** YYYY-NNNNN -> YYYYNNNNN for bills */ ?>

      CustID:               <?= $model->client->comptabilite == '' ? 'UNKNOWN' : $model->client->comptabilite ?>

      Comment:              <?= $model->note ?>

      PeriodID:             <?= substr($model->created_at, 5, 2) /** YYYY-MM-DD -> MM; period = month */?>

      DateDoc:              <?= $model->created_at ?>

      DateDue:              <?= $model->due_date ?>

      Piece:                <?= str_replace('-', '', $model->name) ?>

      CrcyDoc:              EUR
      AmountCrcyDoc:        <?= $amount ?>

      AmountCrcyBase:       <?= $amount ?>

}
<?= $this->render('_extract_bill_lines', ['model' => $model->getDocumentLines()]) ?>
}
