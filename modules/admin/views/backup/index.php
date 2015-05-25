<?php

use app\models\Backup;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BackupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Backups');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="backup-index">

    <h1><?= Html::encode($this->title) ?></h1>
    </p>
	<div class="parameter-form">

	    <?php $form = ActiveForm::begin(['action' => Url::to(['create'])]); ?>

	    <?= $form->field($model, 'note')->textInput(['maxlength' => 160]) ?>

	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('store', 'Create Backup'), ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'filename',
            'note',
            'created_at',
            'status',

            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete} {restore}',
				'buttons' => [
					'restore' => function ($url, $model) {
						return Backup::getDbName('yii2print') ? 
									Html::a('<span class="glyphicon glyphicon-warning-sign"></span>', $url, [
										'title' => Yii::t('app', 'Restore database only'),
										'data-confirm' => Yii::t('store', 'Restore database?')
									]) : '';
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) {
					$url = '';
					switch($action) {
						case 'restore':
							$url = Url::to(['restore-dev', 'id' => $model->id]);
							break;
					}
					return $url;
				}
			],
        ],
    ]); ?>

</div>
