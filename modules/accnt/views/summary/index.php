<?php

use app\models\AccountLine;
use app\models\Cash;
use app\models\Payment;
use app\models\Account;
use app\models\AccountSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('store', 'Payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">
    <div class="row">
		<?php
		 	if(! isset($print))
				echo $this->render('_search', ['model' => $model]);
		 ?>
    </div>

    <div class="row">
		<?= $summary ?>
    </div>

	<?= $detail ?>

</div>
