<?php
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cuts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this);
?>
<div class="work-line-index">

	<?php $form = ActiveForm::begin(['action' => Url::to(['delete-cuts'])]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//          ['class' => 'yii\grid\SerialColumn'],
//			'id',
            'id',
	        [
				'attribute' => 'work_length',
				'label' => Yii::t('store', 'L'),
	        ],
	        [
				'format' => 'raw',
	            'value' => function ($model, $key, $index, $widget) {
	                return $this->render('_master-cut', ['model'=>$model]);
	            },
	        ],
			['class' => 'kartik\grid\CheckboxColumn'],
        ],
//          ['class' => 'yii\grid\SerialColumn'],
    ]); ?>

	<?= Html::submitButton('<i class="glyphicon glyphicon-remove"></i> '.Yii::t('store', 'Remove Cuts'), ['class' => 'btn btn-danger']) ?>

    <?php ActiveForm::end(); ?>

</div>