<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLineDetail */

$this->title = Yii::t('store', 'Order Line Details');

?>
<div class="order-line-detail-view">

	<h4 class="order-option">
		<span style="color: #eea236;">Chroma</span>Luxe
	</h4>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>Yii::t('store','ChromaLuxe'),
                'attribute'=>'chroma_id',
                'value'=> $model->chroma ? $model->chroma->libelle_long : Yii::t('store', 'None'), //'chroma.libelle_long',
            ],
            'price_chroma',
            [
                'attribute'=>'corner_bool',
				'value' => Yii::t('store', $model->corner_bool ? 'Yes' : 'No'),
            ],
            'price_corner',
            [
                'attribute'=>'renfort_bool',
				'value' => Yii::t('store', $model->renfort_bool ? 'Yes' : 'No'),
            ],
            'price_renfort',
            [
                'label'=>Yii::t('store','Frame'),
                'attribute'=>'renfort_bool',
                'value'=> $model->frame ? $model->frame->libelle_long : Yii::t('store', 'None'), //'frame.libelle_long',
            ],
            'price_frame',
            [
                'attribute'=>'montage_bool',
				'value' => Yii::t('store', $model->montage_bool ? 'Yes' : 'No'),
            ],
            'price_montage',
            [
                'label'=>Yii::t('store','Support'),
                'attribute'=>'support_id',
                'value'=> $model->support ? $model->support->libelle_long : Yii::t('store', 'None'), //'support.libelle_long',
            ],
        ],
    ]) ?>

</div>
