<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */
?>

    <div class="row">
	
        <div class="col-lg-8">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => $model->item->libelle_long,
	        'type' => $model->order->getStatusColor(),
	    ],				
		'buttons1' => '',
        'attributes' => [
            'work_width',
            'work_height',
            'note',
            'quantity',
            'unit_price',
            'vat',
            'price_htva',
            [
                'label'=>Yii::t('store','Extra'),
                'attribute'=>'extra_type',
	            'value'=> $model->getExtraDescription(),
            ],
            [
                'label'=>Yii::t('store','Price HTVA'),
                'attribute'=>'extra_htva',
	            'value'=> round($model->price_htva + $model->extra_htva, 2),
            ],
            [
                'label'=>Yii::t('store','Price TVAC'),
                'attribute'=>'extra_htva',
	            'value'=> round(($model->price_htva + $model->extra_htva) * (1 + $model->vat/100), 2),
            ],
        ],
    ]) ?>

		</div>

    <div class="row">
	
        <div class="col-lg-4">

			<?= $this->render('_pictures', [
					'model' => $model,
				])
			?>

		</div>

	</div>

