<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ItemTask */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Item Task',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Item Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
