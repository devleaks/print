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
			$transaction = Yii::$app->db->beginTransaction();
			echo 'Updating '.$model->document_type.' '.$model->name.' €'.$model->getBalance().' ('.$model->status.'-';
			if($work = $model->getWorks()->one()) {
				echo '>work='.$work->status.'-';
			}
			$model->setStatus(Order::STATUS_TOPAY);
			echo '>'.$model->status.')
';
			$transaction->commit();
		}
    }

}