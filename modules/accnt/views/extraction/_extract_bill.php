<?php
use app\models\Document;
/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>

Sales:
{
Header:
{
      JrnlID:               <?= $model->document_type == Document::TYPE_BILL ? 'FV1' : ($model->document_type == Document::TYPE_CREDIT ? 'NV1' : '???') ?>

      DocType:              <?= $model->document_type == Document::TYPE_BILL ? 1 : ($model->document_type == Document::TYPE_CREDIT ? 2 : 0) ?>

      DocNumber:            <?= str_replace('-', '', $model->name) ?>

      CustID:               <?= $model->client->comptabilite == '' ? 'UNKNOWN' : $model->client->comptabilite ?>

      Comment:              <?= $model->note ?>

      PeriodID:             <?= substr($model->updated_at, 5, 2) /* YYYY-MM-DD -> MM */?>

      DateDoc:              <?= $model->created_at ?>

      DateDue:              <?= $model->due_date ?>

      Piece:                <?= str_replace('-', '', $model->name) ?>

      CrcyDoc:              EUR
      AmountCrcyDoc:        <?= $model->price_tvac ?>

      AmountCrcyBase:       <?= $model->price_tvac ?>

}
<?= $this->render('_extract_bill_lines', ['model' => $model->getDocumentLines()]) ?>
}
