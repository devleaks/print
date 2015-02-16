<?php

namespace app\commands;

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

}