<?php
use app\models\Work;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\icons\Icon;

Icon::map($this);

$this->title = Yii::t('store', 'Works');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-default-index">
    <h1><?= Yii::t('store', 'Works and Tasks') ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'client.nom',
            'due_date',
            'price_htva',
            // 'vat',
            // 'created_at',
            //'updated_at',
        [
            'label' => Yii::t('store', 'Tasks'),
            'value' => function ($model, $key, $index, $widget) {
					$ret = '';
					if( $work = $model->getWorks()->one() ) {
						$ret .= $work->getTaskIcons(true, true);
					}
					return $ret;
	        },
            'format' => 'raw',
        ],
        [
            'label' => Yii::t('store', 'Work'),
            'value' => function ($model, $key, $index, $widget) {
					if( $work = $model->getWorks()->one() ) {
		 				return Html::a(Yii::t('store', 'Details'), ['/work/work/view', 'id' => $work->id], ['class' => 'btn-sm btn-primary']);
					} else {
		 				return Html::a(Yii::t('store', 'Submit work'), ['/order/order/submit', 'id' => $model->id], [
		                        'class' => 'btn btn-primary',
		                        'data-method' => 'post',
		                        'data-confirm' => Yii::t('store', 'Submit work?')
		                    ]);
					}
	        },
            'format' => 'raw',
        ],
		],
    ]); ?>

</div>
