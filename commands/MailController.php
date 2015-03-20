<?php

namespace app\commands;

use Yii;
use app\models\Order;
use yii\console\Controller;

class MailController extends Controller {

	/**
	 * Send email that order is completed if close to due_date
	 *
	 * Note: Checks that we are close to due date is done inside notify()
	 *
	 */
    public function actionSend() {
		foreach(Order::find()->andWhere(['status' => Order::STATUS_NOTIFY])->each() as $model) {
			//echo 'Trying...'.$model->name;
			Yii::trace('Trying...'.$model->name, 'MailController::actionSend');
			if($model->notify(true)) {
				echo 'Mail sent for '.$model->name.' to '.$model->client->email.'.';
				$model->setStatus(Order::STATUS_TOPAY);
			} // else echo '. ';
		}
    }

}