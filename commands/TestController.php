<?php

namespace app\commands;

use app\models\Order;
use app\models\Document;
use app\models\Account;
use app\models\Payment;

use yii\console\Controller;
use Yii;

class TestController extends Controller {

    public function actionEmail($email = null) {
		$mail = Yii::$app->mailer->compose()
		    ->setFrom( Yii::$app->params['fromEmail'] )
		    ->setTo(  $email ? $email : Yii::$app->params['testEmail'])
			->setReplyTo(  YII_ENV_DEV ? Yii::$app->params['testEmail'] : Yii::$app->params['replyToEmail'] )
		    ->setSubject('Test')
			->setTextBody('No body.')
			->send();
    }


	public function actionUpdate() {
		foreach(Order::find()->each() as $doc)
			$bill = $doc->getBill();
			if($doc->status == $doc::STATUS_CLOSED) {
				if($bill) {
					$bill->setStatus($doc::STATUS_TOPAY);
				} else {
					$doc->setStatus($doc::STATUS_TOPAY);
				}
				echo "Document ".$doc->document_type.' '.$doc->name." updated.\n";
			} elseif ($doc->status == $doc::STATUS_TOPAY) {
				if($bill) {
					$bill->setStatus($doc::STATUS_TOPAY);
					$doc->setStatus($doc::STATUS_CLOSED);
					echo "Document ".$doc->document_type.' '.$doc->name." updated.\n";
				}
			}
	}


	public function actionDuplicate() {
		if($order = Order::findOne(0)) {
			for($i = 1; $i <= 12; $i++) {
				$new = $order->deepCopy();
				//$new->document_type = $new::TYPE_TICKET;
				$new->due_date = '2014-'.sprintf("%02d", $i).'-15 00:00:00';
				$new->created_at = '2014-'.sprintf("%02d", $i).'-15 00:00:00';
				$new->name = '2014-CA-'.sprintf("%02d", $i);
				$new->save();
				if($ol = $new->getDocumentLines()->one()) {
					$ol->note = '2014-CA-'.sprintf("%02d", $i);
					$ol->save();
				}
			}
		}
	}
	/**
	const STATUS_CREATED = 'CREATED';	
	const STATUS_OPEN = 'OPEN';
	const STATUS_TODO = 'TODO';
	const STATUS_BUSY = 'BUSY';
	const STATUS_DONE = 'DONE';
	const STATUS_NOTIFY = 'NOTIFY';
	const STATUS_TOPAY = 'TOPAY';
	const STATUS_CANCELLED = 'CANCELLED';
	const STATUS_CLOSED = 'CLOSED';
	const STATUS_WARN = 'WARN';
	*/
	public function actionFixPaymentStatus() {
		foreach(Document::find()->andWhere(['status' => [Document::STATUS_DONE, Document::STATUS_TOPAY]])
								->andWhere(['not', ['document_type' => [Document::TYPE_BID]]])
								->each() as $doc) {
			if($model = Document::findDocument($doc->id)) {
				if($model->getBalance() == 0 && $model->status != Document::STATUS_CLOSED) {
					$transaction = Yii::$app->db->beginTransaction();
					echo 'Updating '.$model->document_type.' '.$model->name.' €'.$model->getBalance().' ('.$model->status.'-';
					if($work = $model->getWorks()->one()) {
						echo '>work='.$work->status.'-';
					}
					$model->setStatus(Document::STATUS_TOPAY);
					echo '>'.$model->status.')
';
					$transaction->commit();
				}
			}
		}
    }


	function actionClearOldPayments() {
		foreach(Document::find()->andWhere(['<', 'document.created_at', '2015-08-01 00:00:00'])
								->andWhere(['status' => [Document::STATUS_DONE, Document::STATUS_TOPAY, Document::STATUS_CLOSED]])
								->andWhere(['not', ['document_type' => [Document::TYPE_BID]]])
								->each() as $model) {
			if(($balance = $model->getBalance()) > 0) {
				echo 'Updating '.$model->document_type.' '.$model->name.' €'.$model->getBalance().' DATE='.$model->created_at.' ('.$model->status.'-';
				$transaction = Yii::$app->db->beginTransaction();
				$account = new Account([
					'client_id' => $model->client_id,
					'payment_method' => Payment::METHOD_OLDSYSTEM,
					'payment_date' => date('Y-m-d'),
					'amount' => $balance,
					'status' => 'CREDIT',
				]);
				$account->save();
				$account->refresh();
				$payment = new Payment([
					'sale' => $model->sale,
					'client_id' => $model->client_id,
					'payment_method' => Payment::METHOD_OLDSYSTEM,
					'amount' => $balance,
					'status' => Payment::STATUS_PAID,
					'account_id' => $account->id,
				]);
				$payment->save();
				echo '>PAYMENT='.$balance.'-';
				$model->setStatus(Document::STATUS_TOPAY);
				$model->save();
				echo '>'.$model->status.')
';
				$transaction->commit();
			} else {
				echo '>OK!
';
			}
		}
	}
    
}