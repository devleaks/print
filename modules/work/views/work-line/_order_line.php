<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrderLine */
?>
<div class="order-line-view">

    <div class="row">
	
		<div class="col-lg-8">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order.name',
        	'item.libelle_long',
            'unit_price',
            'note',
            'quantity',
            'work_width',
            'work_height',
            [
                'attribute'=>'note',
                'label'=>Yii::t('store','Options'),
                'value'=> $model->getOrderLineDetails()->one() != null ? $model->getOrderLineDetails()->one()->getDescription() : '',
			],
        ],
    ]) ?>
		</div>

        <div class="col-lg-4">

	<?= $this->render('_pictures', [
			'model' => $model,
		])
	?>
		</div>
	</div>

</div>
