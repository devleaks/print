<?php
use app\models\Update;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('store', 'Update Application');
$this->params['breadcrumbs'][] = $this->title;

$apphomedir = Yii::getAlias('@app');
$ret = `cd $apphomedir ; git tag`;
$versions = explode("\n", trim($ret));
$versions[] = Update::LATEST;
?>
<div class="admin-default-index">
	
    <h1><?= $this->title ?></h1>

<p><?php echo 'Current Version is '.`cd $apphomedir ; git describe --tags`; ?></p> 

<?php $form = ActiveForm::begin(['action' => Url::to(['/admin/update/update'])]); ?>

<?= GridView::widget([
	'id' => 'grid-versions',
    'dataProvider' => new ArrayDataProvider(['allModels' => array_reverse($versions)]),
	'summary' => false,
    'columns' => [
        [
			'class' => 'kartik\grid\RadioColumn',
			'name' => 'version',
			'radioOptions' => function($model, $key, $index, $column) {
			    return ['checked' => ($model == Update::LATEST), 'value' => $model];
			}
		],
        [
			'label' => 'Version',
            'value' => function($model, $key, $index, $widget) {
				return $model;
			},
        ],
    ],
]); ?>


<div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-warning-sign"></i> '.Yii::t('store', 'Update Application'),
				[
					'class' => 'btn btn-danger',
					'title' => Yii::t('store', 'Update Software'),
					'data' => ['confirm' => Yii::t('store', 'Are you sure you want to upgrade the application?')],
				]) ?>
</div>

<?php ActiveForm::end(); ?>

</div>