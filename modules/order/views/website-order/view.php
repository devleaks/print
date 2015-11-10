<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\Parameter */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Web Transfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
	        'order_date',
            'name',
            'company',
            'address',
            'city',
            'vat',
            'phone',
            'email',
            'delivery',
            'promocode',
            'comment',
            'clientcode',
            [
                'attribute'=>'convert_errors',
                'value'=> VarDumper::dumpAsString($model->convert_errors, 4, true), // '<span class="rednote">'.$model->convert_errors.'</span>',
				'format' => 'raw',
            ],
            'status',
        ],
    ]) ?>

	<?= $this->render('_list', [
	        'dataProvider' => new ActiveDataProvider([
					'query' => $model->getWebsiteOrderLines()
			]),
			'order' => $model,
	    ]);
	?>

</div>
