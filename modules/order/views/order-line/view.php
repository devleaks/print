<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Document;
use app\models\Item;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */

$this->title = $model->getItem()->one()->libelle_long;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->order->order_type, true)), 'url' => ['order/'.strtolower($model->order->order_type).'s']];
$this->params['breadcrumbs'][] = ['label' => $model->getOrder()->one()->name, 'url' => Url::to(['/order/order/view', 'id' => $model->order_id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-line-view">

    <?php if($model->order->status == Document::STATUS_OPEN): ?>
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
    <?php endif; ?>

	<?= $this->render('_view', [
			'model' => $model,
		])
	?>

	<?php
		if( $old = $model->getOrderLineDetails()->one() ) {
		 	$chroma_item  = Item::find()->where(['categorie'=>'ChromaLuxe'])->one();
			$fineart_item = Item::find()->where(['categorie'=>'Fine Arts'])->one();
			$detail = $model->getDetail();
		 	if($model->item_id == $chroma_item->id)
				echo $this->render('../order-line-detail/_view_chroma', [
				    'model' => $detail
			    ]);
			else if($model->item_id == $fineart_item->id)
				echo $this->render('../order-line-detail/_view_fineart', [
				    'model' => $detail
			    ]);
		}
	?>

</div>
