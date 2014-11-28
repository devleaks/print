<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Extraction */

$this->title = Yii::t('store', 'Create {modelClass}', [
    'modelClass' => 'Extraction',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Extractions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extraction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
