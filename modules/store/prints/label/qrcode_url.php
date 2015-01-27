<?php
/* @var $this yii\web\View */
/* @var $model app\models\Document */

use yii\helpers\Url;
?>

<?= Url::to(['/order/document/view', 'id' => $model->id], true) ?>
