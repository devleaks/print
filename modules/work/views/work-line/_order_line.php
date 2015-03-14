<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */
?>
<div class="document-line-view">

    <div class="row">
	
		<div class="col-lg-8">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>Yii::t('store','Order'),
                'value'=> $model->document->name,
			],
            [
                'label'=>Yii::t('store','Client'),
                'value'=> $model->document->client->nom,
			],
            [
                'label'=>Yii::t('store','Due Date'),
                'value'=> $model->due_date,
				'format' => 'date'
			],
            [
                'label'=>Yii::t('store','Item'),
                'value'=> $model->item->libelle_long,
			],
            'quantity',
            'work_width',
            'work_height',
            [
                'label'=>Yii::t('store','Options'),
                'value'=> $model->getDocumentLineDetails()->one() != null ? $model->getDocumentLineDetails()->one()->getDescription() : '',
			],
            [
                'label'=>Yii::t('store','Item'),
                'value'=> $model->item->libelle_long,
			],
            [
                'attribute'=>'note',
                'value'=> $model->note ? '<span class="rednote">'.$model->note.'</span>' : '',
				'format' => 'raw',
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
