<?php

namespace app\commands;

use app\models\Order;
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
}