<?php

use app\models\User;
use app\models\WorkLineSearch;
use kartik\detail\DetailView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Work */

$this->title = $model->document->name . ($model->document->client ? ' — ' .$model->document->client->nom : '');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'All Works'), 'url' => ['index', 'sort' => '-updated_at']];
if(isset($order_line)) {
	$this->params['breadcrumbs'][] = ['label' => $model->getDocument()->one()->name, 'url' => Url::to(['/work/work/view', 'id' => $model->id])];
	$this->params['breadcrumbs'][] = $order_line->getItem()->one()->libelle_long;
} else {
	$this->params['breadcrumbs'][] = $this->title;
}

$can_view = User::hasRole(['manager', 'admin']);

?>
<div class="work-view">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="row">
	<div class="col-lg-8">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute'=>'document_id',
                'label'=>Yii::t('store','Order'),
//				'value'=>Html::a($model->getDocument()->one()->name, Url::to(['/order/document/view', 'id' => $model->document_id])),
                'value'=>$can_view ? Html::a($model->getDocument()->one()->name, Url::to(['/order/document/view', 'id' => $model->document_id])) : $model->getDocument()->one()->name,
				'format' => 'raw',
            ],
            [
                'label'=>Yii::t('store','Client'),
                'attribute'=>'document_id',
                'value'=> $model->document->client ? $model->document->client->nom : '',
				'format' => 'raw',
			],
            [
                'label'=>Yii::t('store','Note'),
                'attribute'=>'document_id',
                'value'=> $model->document->note ? '<span class="rednote">'.$model->document->note.'</span>' : '',
				'format' => 'raw',
			],
//            'document_id',
            [
                'attribute'=>'created_at',
				'value' => Yii::$app->formatter->asDateTime($model->created_at).' '.Yii::t('store', 'by').' '.($model->createdBy ? $model->createdBy->username : ''),
            ],
            [
                'attribute'=>'updated_at',
				'value' => Yii::$app->formatter->asDateTime($model->updated_at).' '.Yii::t('store', 'by').' '.($model->updatedBy ? $model->updatedBy->username : ''),
            ],
            [
                'attribute'=>'due_date',
				'value' => $model->due_date,
				'format' => 'date'
            ],
            [
                'attribute'=>'status',
                'label'=>Yii::t('store','Order'),
                'value'=>$model->getStatusLabel(),
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

	<div class="row">
		<div class="col-lg-12">
<?php
	$searchModel = new WorkLineSearch();
	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

	if(isset($order_line))
	    $dataProvider->query->andWhere(['work_id' => $model->id])->andWhere(['document_line_id' => $order_line->id]); //->orderBy('position');
	else
	    $dataProvider->query->andWhere(['work_id' => $model->id]); //->orderBy('position');

    echo $this->render('../work-line/list', [
        'dataProvider' => $dataProvider,
		'searchModel' => $searchModel,
    ]);
?>
		</div>
	</div>

</div>
