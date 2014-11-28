<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorkLine */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Task',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-line-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
