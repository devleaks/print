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
		foreach(Order::find()->andWhere(['status' => Order::STATUS_NOTIFY])->andWhere(['notified_at' => null])->each() as $model) {
			//echo 'Trying...'.$model->name;
			Yii::trace('Trying...'.$model->name, 'MailController::actionSend');
			if($model->notify(true)) {
				echo 'Mail sent for '.$model->name.' to '.$model->getNotificationEmail().".\n";
				$model->setStatus(Order::STATUS_TOPAY);
				$model->save();
			} // else echo '. ';
		}
    }

    public function actionNotified() {
		foreach(Order::find()->andWhere(['status' => Order::STATUS_NOTIFY])->andWhere(['not',['notified_at' => null]])->each() as $model) {
			Yii::trace('Updating...'.$model->name, 'MailController::actionSend');
			$model->setStatus(Order::STATUS_TOPAY);
			Yii::trace('Updated to '.$model->status, 'MailController::actionSend');
		}
    }

}