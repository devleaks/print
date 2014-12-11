<?php

use app\models\Payment;
use app\models\PaymentSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
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
			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				$dataProvider = new ActiveDataProvider([
					'query' => Payment::find()
								->andWhere(['>=','created_at',$day_start])
								->andWhere(['<=','created_at',$day_end])
								->andWhere(['payment_method' => $payment_method])
				]);
				echo $this->render($payment_method == Payment::TYPE_ACCOUNT ? '_detail-account' : '_detail', ['dataProvider' => $dataProvider, 'method' => $payment_label]);
			}
		} else
			foreach(Payment::getPaymentMethods() as $payment_method => $payment_label) {
				$dataProvider = new ActiveDataProvider([
					'query' => Payment::find()
								->andWhere(['payment_method' => $payment_method])
				]);
				echo $this->render($payment_method == Payment::TYPE_ACCOUNT ? '_detail-account' : '_detail', ['dataProvider' => $dataProvider, 'method' => $payment_label]);
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
