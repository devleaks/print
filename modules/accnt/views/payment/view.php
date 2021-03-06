<?php

use app\models\Document;

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

if($doc = Document::findBySale($model->sale)) {
	$this->title = Yii::t('store', 'Payment {0} for {1}', [$model->id, $doc->name]);
	$this->params['breadcrumbs'][] = ['label' => $doc->name, 'url' => ['/accnt/payment/sale', 'id' => $doc->id]];
} else {
	$this->title = $model->id;
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('store', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('store', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('store', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sale',
            [
                'label'=>Yii::t('store','Client'),
                'attribute'=>'client_id',
                'value'=> $model->client->nom, //'chroma.libelle_long',
            ],
            'amount',
            'payment_method',
            'status',
            [
                'attribute'=>'created_at',
				'value' => Yii::$app->formatter->asDateTime($model->created_at).' '.Yii::t('store', 'by').' '.($model->createdBy ? $model->createdBy->username : ''),
            ],
            [
                'attribute'=>'updated_at',
				'value' => Yii::$app->formatter->asDateTime($model->updated_at).' '.Yii::t('store', 'by').' '.($model->updatedBy ? $model->updatedBy->username : ''),
            ],
        ],
    ]) ?>

</div>
