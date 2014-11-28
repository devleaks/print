<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Backup */

$this->title = Yii::t('store', 'Create Backup');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Backups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
