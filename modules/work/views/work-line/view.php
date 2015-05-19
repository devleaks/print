<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WorkLine */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-line-view">

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

    <div class="row">
	
        <div class="col-lg-8">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'note',
                'value'=> '<span class="rednote">'.$model->note.'</span>',
				'format' => 'raw',
            ],
            'work_id',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'status',
            'task_id',
            //'document_line_id',
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
