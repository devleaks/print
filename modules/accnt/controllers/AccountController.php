<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\AccountLine;
use app\models\Client;
use app\models\Bill;
use app\models\Credit;
use app\models\Payment;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends Controller
{
/*
select 'A',id, client_id, price_tvac as amount, created_at from document
where client_id = 3019
union
select 'B',sale,client_id,amount,created_at from payment
where client_id = 3019
order by 5 desc
*/
	public function actionClient($id) {
		$client = Client::findOne($id);
		if(!$client)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$accountLines = [];
		$sales = [];

		foreach(Bill::find()->andWhere(['client_id'=>$client->id])->andWhere(['!=', 'status', Credit::STATUS_OPEN])->each() as $document) {
			$color = ($document->getBalance() <= 0) ? 'success' : 'warning';
			$accountLines[] = new AccountLine([
				'note' => /*'B '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
				'amount' => - $document->getAmount(),
				'date' => $document->created_at,
				'ref' => $document->id,
			]);
			$sales[] = $document->sale;
		}

		foreach(Credit::find()->andWhere(['client_id'=>$client->id])->andWhere(['!=', 'status', Credit::STATUS_OPEN])->each() as $document) {
			$color = ($document->getBalance() >= 0) ? 'success' : 'info';
			$accountLines[] = new AccountLine([
				'note' => /*'C '.*/Html::a('<span class="label label-'.$color.'">'.$document->name.'</span>', Url::to(['/order/document/view', 'id' => $document->id])),
				'amount' => - $document->getAmount(),
				'date' => $document->created_at,
				'ref' => $document->id,
			]);
			$sales[] = $document->sale;
		}

		foreach($client->getPayments()->andWhere(['sale' => $sales])->each() as $payment) {
			$doc = $payment->getDocument()->one();
			if($doc) {
				if($doc->document_type == $doc::TYPE_CREDIT)
					$color = ($doc->getBalance() >= 0) ? 'success' : 'info';
				else
					$color = ($doc->getBalance() <= 0) ? 'success' : 'warning';
			} else
				$color = 'info';
				
			$note = $doc ? Html::a('<span class="label label-'.$color.'">'.$doc->name.'</span>', Url::to(['/order/document/view', 'id' => $doc->id])).' - '.$payment->getPaymentMethod()
			             : ($payment->note ? $payment->note : '<span class="label label-'.$color.'">'.$payment->payment_method.'</span>'.' - '.$payment->sale);
			$accountLines[] = new AccountLine([
				'note' => /*'P '.*/$note,
				'amount' => $payment->amount,
				'date' => $payment->created_at,
				'ref' => $payment->id,
			]);
		}

		/* These are the outstanding "excess" payments */
		foreach($client->getPayments()->andWhere(['status' => Payment::STATUS_OPEN])->each() as $payment) {
			$reimburse = Html::a('<span class="label label-primary">'.Yii::t('store', 'Reimburse').'</span>', Url::to(['reimburse', 'id' => $payment->id]), ['title' => Yii::t('store', 'Reimburse').'-'.$payment->sale]);
			$note = $payment->note ? $payment->note : '<span class="label label-'.$color.'">'.$payment->payment_method.'</span>'.' - '.$reimburse;
			$accountLines[] = new AccountLine([
				'note' => /*'P '.*/$note,
				'amount' => $payment->amount,
				'date' => $payment->created_at,
				'ref' => $payment->id,
			]);
		}	
		
		uasort($accountLines, function($a, $b) { return $a->date > $b->date; }); //wow
		
		// build summary column. No other method.
		$tot = 0;
		foreach($accountLines as $l) {
			$l->account = ($tot += $l->amount);
		}

        return $this->render('client', [
            'dataProvider' => new ArrayDataProvider(['allModels' => $accountLines]),
			'client' => $client
        ]);
	}
	
	public function actionReimburse($id) {
		Yii::$app->session->setFlash('error', Yii::t('store', 'Procédure encore à développer. Pierre 23-JAN-2015.'));
		return $this->redirect(Yii::$app->request->referrer);
	}

}
