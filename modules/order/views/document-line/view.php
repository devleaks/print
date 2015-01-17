<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Document;
use app\models\Item;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentLine */

$this->title = $model->getItem()->one()->libelle_long;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', Document::getTypeLabel($model->document->document_type, true)),
	'url' => ['document/'.strtolower($model->document->document_type).'s']];
$this->params['breadcrumbs'][] = ['label' => $model->getDocument()->one()->name, 'url' => Url::to(['/order/document/view', 'id' => $model->document_id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-line-view">

    <?php if($model->document->status == Document::STATUS_OPEN): ?>
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
		if( $detail = $model->getDetail() ) {
		 	if($model->isChromaLuxe())
				echo $this->render('../document-line-detail/_view_chroma', [
				    'model' => $detail
			    ]);
			else if($model->isTirage(true)) // tirage OR canvas
				echo $this->render('../document-line-detail/_view_fineart', [
				    'model' => $detail
			    ]);
		}
	?>

</div>
