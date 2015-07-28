<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLineDetail */

$this->title = Yii::t('store', 'Order Line Details');

?>
<div class="document-line-detail-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>Yii::t('store','Tirage'),
                'attribute'=>'tirage_id',
                'value'=> $model->tirage ? $model->tirage->libelle_long : Yii::t('store', 'None'), //'tirage.libelle_long',
            ],
            'price_tirage',
            [
                'label'=>Yii::t('store','Chassis'),
                'attribute'=>'chassis_id',
                'value'=> $model->chassis ? $model->chassis->libelle_long : Yii::t('store', 'None'), //'tirage.libelle_long',
            ],
            'price_chassis',
            [
                'label'=>Yii::t('store','Finish'),
                'attribute'=>'finish_id',
                'value'=> $model->finish ? $model->finish->libelle_long : Yii::t('store', 'None'), //'finish.libelle_long',
            ],
            [
                'label'=>Yii::t('store','Support'),
                'attribute'=>'support_id',
                'value'=> $model->support ? $model->support->libelle_long : Yii::t('store', 'None'), //'support.libelle_long',
            ],
            'price_support',
            [
                'attribute'=>'corner_bool',
				'value' => Yii::t('store', $model->corner_bool ? 'Yes' : 'No'),
            ],
            [
                'label'=>Yii::t('store','Frame'),
                'attribute'=>'frame_id',
                'value'=> $model->frame ? $model->frame->libelle_long : 'None', //'frame.libelle_long',
            ],
            'price_frame',
            [
                'attribute'=>'montage_bool',
				'value' => Yii::t('store', $model->montage_bool ? 'Yes' : 'No'),
            ],
            'price_montage',
            [
                'attribute'=>'renfort_id',
				'value' => $model->renfort ? $model->renfort->libelle_long : Yii::t('store', 'None'),
            ],
            'price_renfort',
            [
                'label'=>Yii::t('store','Protection'),
                'attribute'=>'protection_id',
                'value'=> $model->protection ? $model->protection->libelle_long : Yii::t('store', 'None'), //'protection.libelle_long',
            ],
            'price_protection',
            [
                'label'=>Yii::t('store','Film UV'),
                'attribute'=>'filmuv_bool',
                'value'=> Yii::t('store', $model->filmuv_bool ? 'Yes' : 'No'),
            ],
            'price_filmuv',
        ],
    ]) ?>

</div>
