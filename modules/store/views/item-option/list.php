<?php

use app\models\ItemOption;
use app\models\Option;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$dataProvider = new ActiveDataProvider([
	'query' => ItemOption::find()->where(['item_id' => $model->id])->orderBy('position'),
]);

?>
<div class="task-view">

	<p></p>
    <h2><?= Yii::t('store', 'Associated Options') ?></h2>
	<p></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'position',
            'option.name',
            'option.label',
            'option.status',

            ['class' => 'yii\grid\ActionColumn',
             'controller' => 'item-option',
			 'template' => '{update} {delete}'],
        ],
    ]); ?>
	<p></p>

	<?php
		$io = new ItemOption();
		$io->item_id = $model->id;
		echo $this->render('_add', ['model'=>$io]);
	?>

</div>
