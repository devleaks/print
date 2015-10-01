<?php

namespace app\commands;

use app\models\Order;
use app\models\Document;
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
	
	public function actionFixPaymentStatus() {
		foreach(Document::find()->andWhere(['not', ['status' => [Document::STATUS_TODO]]])->andWhere(['not', ['document_type' => [Document::TYPE_BID]]])->each() as $model) {
			if($model->getBalance() == 0 && $model->status != Document::STATUS_CLOSED) {
				echo 'Updating model '.$model->name.'... ';
				$model->setStatus(Document::STATUS_TOPAY);
				echo 'updated to '.$model->status.'.\r';
			}
		}
    }

    
}