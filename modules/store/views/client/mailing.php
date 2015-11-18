<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Clients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'type'=>GridView::TYPE_PRIMARY,
	        'heading'=>$this->title,
	    ],
		'toolbar'=> [
	        '{export}',
	        '{toggleData}',
	    ],
        'columns' => [
            'prenom',
            'nom',
            'autre_nom',
            [
				'attribute' => 'lang',
				'filter' => ['fr' => 'Français', 'nl' => 'Nederlands', 'en' => 'English'],
				'hAlign' => 'center',
			],
            'email:email',
        ],
    ]); ?>

</div>
