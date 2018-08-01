<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Clients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Management'), 'url' => ['..']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <?= DynaGrid::widget([
        'gridOptions'=>[
          'dataProvider'=>$dataProvider,
          'filterModel'=>$searchModel,
          'showPageSummary'=>true,
          'panel'=>[
              'heading'=>'<h3 class="panel-title">'.$this->title.'</h3>'
          ],
          'toolbar'=> [
              '{dynagrid}',
              '{export}',
              '{toggleData}',
          ],
      ],
      'options' => [
          'id' => 'client'
      ],
      'columns' => [
          'titre',
          'prenom',
          'nom',
          'autre_nom',
          [
            'attribute' => 'adresse',
            'visible' => false
          ],
          [
            'attribute' => 'code_postal',
            'visible' => false
          ],
          [
            'attribute' => 'localite',
            'visible' => false
          ],
          'pays',
          [
    				'attribute' => 'lang',
    				'filter' => ['fr' => 'Français', 'nl' => 'Nederlands', 'en' => 'English'],
    				'hAlign' => 'center',
            'value' => function ($model, $key, $index) { 
                return Yii::t('store', $model->lang);
            }
    			],
          [
            'attribute' => 'non_assujetti_tva',
            'filter' => ['1' => 'Non', '0' => 'Oui'],
            'hAlign' => 'center',
            'value' => function ($model, $key, $index) { 
                return $model->non_assujetti_tva ? 'Non' : 'Oui';
            },
            'label' => 'TVA'
          ],
          'email:email',
      ],
    ]); ?>

</div>
