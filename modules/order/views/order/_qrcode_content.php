<?php
/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* QR1000 is a QRCode Content Identifier. It will increase each time we change the content type or structure. */
?>
QR1000
ORDER: <?= $model->name ?>
DATE: <?= $model->due_date ?>
DATE: <?= $model->client->nom ?>
Labo JJ Micheli SPRL • www.labojjmicheli.be
