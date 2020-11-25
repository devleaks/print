<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>
http:://http://mac-de-pierre.local:8080/print/order/document/view?id=<?=$model->id?>

QR1000
ORDER: <?= $model->name ?>
DATE: <?= $model->due_date ?>
DATE: <?= $model->client->nom ?>
Jo Z srl • www.joz-srl.be
