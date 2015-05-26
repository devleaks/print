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
$cash_amount = 0;
$cash_count  = 0;
$cashLines = [];
$is_print = isset($print) ? '_print' : '';
/*
<h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Payment'), ['create'], ['class' => 'btn btn-success']) ?></h1>
*/
?>
<div class="payment-index">
    <div class="row">
		<?php
		 	if(! isset($print))
				echo $this->render('_search', ['model' => $model]);
		 ?>
    </div>

<?php

	//
	// Part 1: Cash
	//
	if($searchModel->created_at != '') {
		$day_start = $searchModel->created_at. ' 00:00:00';
		$day_end   = $searchModel->created_at. ' 23:59:59';
		
		foreach(Cash::find()
			->andWhere(['>=','created_at',$day_start])
			->andWhere(['<=','created_at',$day_end])->each() as $cash) {
			$cashLines[] = new AccountLine([
				'note' => $cash->note,
				'amount' => $cash->amount,
				'date' => $cash->created_at,
				'ref' => $cash->sale ? $cash->id : null,
			]);
			$cash_amount += $cash->amount;
			$cash_count++;
		}
	} else {
		foreach(Cash::find()->each() as $cash) {
			$cashLines[] = new AccountLine([
				'note' => $cash->note,
				'amount' => $cash->amount,
				'date' => $cash->created_at,
				'ref' => $cash->sale ? $cash->id : null,
			]);
			$cash_amount += $cash->amount;
			$cash_count++;
		}
	}
	?>

	<div class="row">
		<?= $this->render('_summary'.$is_print, ['searchModel' => $searchModel, 'cash_amount' => $cash_amount, 'cash_count' => $cash_count]) ?>
	</div>
	
	<?php
	//
	// Part 2a: Cash
	//
	$dataProvider = new ArrayDataProvider([
		'allModels' => $cashLines,
	]);
	echo $this->render('_detail-cash'.$is_print, ['dataProvider' => $dataProvider, 'label' => Yii::t('store', 'Cash')]);

	


	//
	// Part 2b: Everything but cash
	//
	if($searchModel->created_at != '') {
		$day_start = $searchModel->created_at. ' 00:00:00';
		$day_end   = $searchModel->created_at. ' 23:59:59';
		
		foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
			if($payment_method != Payment::CASH) {
				$dataProvider = new ActiveDataProvider([
					'query' => Account::find()
								->andWhere(['>=','created_at',$day_start])
								->andWhere(['<=','created_at',$day_end])
								->andWhere(['payment_method' => $payment_method])
				]);
				echo $this->render('_detail'.$is_print, ['dataProvider' => $dataProvider, 'method' => $payment_method, 'label' => $payment_label]);
			}
		}
	} else {			
		foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				$dataProvider = new ActiveDataProvider([
					'query' => Account::find()
								->andWhere(['payment_method' => $payment_method])
				]);
				echo $this->render('_detail'.$is_print, ['dataProvider' => $dataProvider, 'method' => $payment_method, 'label' => $payment_label]);
		}
	}
?>


</div>
