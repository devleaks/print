<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\PriceListItem;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PriceListItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataProvider = new ActiveDataProvider([
	'query' => $model->getPriceListItems()->orderBy('position'),
]);



?>
<div class="price-list-item-index">

    <h2><?= Html::encode(Yii::t('store', 'Items')) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'price_list_id',
            'position',
            'item.libelle_long',
            // 'status',
            // 'created_at',
            // 'updated_at',

			[
				'class' => 'yii\grid\ActionColumn',
             	'controller' => 'price-list-item',
			 	'template' => '{update} {delete}'
			]
		],
	]); ?>

	<?php
		$pli = new PriceListItem();
		$pli->price_list_id = $model->id;
		echo $this->render('_add', ['model'=>$pli]);
	?>

</div>
