<?php

namespace app\commands;

use app\models\Bill;
use yii\console\Controller;
use Yii;

class PrepController extends Controller {

    public function actionCleanBills() {
	foreach(Bill::find()->each() as $bill) 
		$bill->deleteCascade();
    }


}