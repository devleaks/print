<?php

use yii\helpers\Url;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
?>
<div class="document-line-inplace-update">

    <?php $form = ActiveForm::begin([
		'type'    => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
		'action' => Url::to(['document-line/update', 'id' => $model->id]),
		'id' => 'documentline-form',
	]); ?>

    <?= $this->render('../document-line/_update', [
        'model' => $model,
        'form' => $form,
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
