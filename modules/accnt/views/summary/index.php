<?php

use app\models\AccountLine;
use app\models\Cash;
use app\models\Payment;
use app\models\PaymentSearch;
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
/*
<h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('store', 'Create Payment'), ['create'], ['class' => 'btn btn-success']) ?></h1>
*/
?>
<div class="payment-index">
    <div class="row">
		<?= $this->render('_search', ['model' => $searchModel]) ?>
    </div>


    <div class="row">
		<?= $this->render('_summary', ['searchModel' => $searchModel]) ?>
    </div>



<?php
		if($searchModel->created_at != '') {
			$day_start = $searchModel->created_at. ' 00:00:00';
			$day_end   = $searchModel->created_at. ' 23:59:59';
			
			// Do cash first
			$cashLines = [];
			$payment_ids = [];

			foreach(Payment::find()
				->andWhere(['>=','created_at',$day_start])
				->andWhere(['<=','created_at',$day_end])
				->andWhere(['payment_method' => Payment::CASH])->each() as $payment) {
					if($payment->cash_id)
						$payment_ids[] = $payment->cash_id;
					$cashLines[] = new AccountLine([
						'note' => $payment->note,
						'amount' => $payment->amount,
						'date' => $payment->created_at,
						'ref' => $payment->sale,
					]);
				}
				;

			// Get cash operations that are NOT a payment
			foreach(Cash::find()
				->andWhere(['>=','created_at',$day_start])
				->andWhere(['<=','created_at',$day_end])
				->andWhere(['not', ['id' => $payment_ids]])->each() as $cash) {
				$cashLines[] = new AccountLine([
					'note' => $cash->note,
					'amount' => $cash->amount,
					'date' => $cash->created_at,
					'ref' => null,
				]);
			}
				
			$dataProvider = new ArrayDataProvider([
				'allModels' => $cashLines,
			]);
			echo $this->render('_detail-cash', ['dataProvider' => $dataProvider, 'method' => Yii::t('store', 'Cash')]);

			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				if($payment_method != Payment::CASH) {
					$dataProvider = new ActiveDataProvider([
						'query' => Payment::find()
									->andWhere(['>=','created_at',$day_start])
									->andWhere(['<=','created_at',$day_end])
									->andWhere(['payment_method' => $payment_method])
					]);
					echo $this->render('_detail', ['dataProvider' => $dataProvider, 'method' => $payment_label]);
				}
			}
		} else
			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				$dataProvider = new ActiveDataProvider([
					'query' => Payment::find()
								->andWhere(['payment_method' => $payment_method])
				]);
				echo $this->render('_detail', ['dataProvider' => $dataProvider, 'method' => $payment_label]);
			}

?>

<?php
	/*
	$dataProvider = new ActiveDataProvider([
		'query' => Payment::find(),
		'pagination' => false
	]);
	echo $this->render('_detail2', ['dataProvider' => $dataProvider]);
	*/
	;
?>


</div>
