<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Bill;
use app\models\Order;
use yii\console\Controller;
use Yii;

class ComptaController extends Controller
{

    public function actionLateBills()
    {
		echo 'actionLateBills';
    }


    public function actionClientAccounts()
    {
		echo 'actionClientAccounts';
    }


    public function actionDailyBalance()
    {
		echo 'actionDailyBalance';
    }


    public function actionPopsyTransfer()
    {
		echo 'actionPopsyTransfer';
    }


    public function actionBillsFromBoms()
    {
		echo "Starting ComptaController::actionBillsFromBoms ..";
        $q = Order::find()->andWhere(['document.bom_bool' => true, 'document.status' => [Order::STATUS_DONE, Order::STATUS_TOPAY]])->select('client_id')->distinct();
		$bills = [];
		foreach($q->each() as $client) {
			$docs = [];

			foreach(Order::find()->andWhere(['document.bom_bool' => true])
								 ->andWhere(['document.status' => [Order::STATUS_DONE, Order::STATUS_TOPAY]])
								 ->andWhere(['client_id' => $client->client_id])
								 ->each() as $doc)
				$docs[] = $doc->id;
	
			$bills[] = Bill::createFromBoms($docs);
			echo 'client:'.$client->client_id.', bill='.$bills[count($bills)-1]->id;
		}
		echo ". done.\r\n";
    }

}