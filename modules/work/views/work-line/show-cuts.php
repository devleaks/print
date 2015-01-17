<?php
use app\models\Task;
use app\models\User;
use app\models\Work;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorkLineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Cuts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this);
?>
<div class="work-line-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//			'id',
            'length',
            'work_line_id',
        ],
    ]); ?>

</div>