<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'client.nom',
            'amount',
            'payment_date',
            'payment_method',
            'note',
            'status',
            'created_at',
            'createdBy.username',
            'updated_at',
            'updatedBy.username',
        ],
    ]) ?>

	<?= $this->render('../payment/_account.php', [
		'dataProvider' => new ActiveDataProvider(['query' => $model->getPayments()]),
		'model' => $model,
	])?>

</div>
