<?php

use app\models\Task;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_id')->hiddenInput() ?>
    <?= $form->field($model, 'task_id')->dropDownList(ArrayHelper::map(Task::find()->asArray()->all(), 'id', 'name'), ['width' => '400px' ]) ?>
    <?= $form->field($model, 'position')->textInput() ?>
    <?= $form->field($model, 'note')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('store', 'Create') : Yii::t('store', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
