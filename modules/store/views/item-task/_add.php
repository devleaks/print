<?php

use app\models\Task;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-item-form">

	<?php $form = ActiveForm::begin([
			'action' => Url::to(['item-task/add'])
          ]);
	?>

    <?= Html::activeHiddenInput($model, 'item_id') ?>
    <?= $form->field($model, 'task_id')->dropDownList(ArrayHelper::map(Task::find()->asArray()->all(), 'id', 'name'), ['width' => '400px' ]) ?>
    <?= $form->field($model, 'position')->textInput() ?>
    <?= $form->field($model, 'note')->textInput() ?>

    <div class="form-group">
    <?= Html::submitButton(Yii::t('store', 'Add Task'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
