<?php

namespace app\modules\accnt\controllers;

use Yii;
use app\models\AccountLine;
use app\models\Bill;
use app\models\Order;
use app\models\Client;
use app\models\Credit;
use app\models\DocumentLine;
use app\models\Item;
use app\models\Payment;
use app\models\Refund;
use app\models\Sequence;
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

		$accountLines = $client->getAccountLines();

        return $this->render('client', [
            'dataProvider' => new ArrayDataProvider(['allModels' => $accountLines]),
			'client' => $client
        ]);
	}
	
	/**
	 * @param boolean $ticket Credit note (0) or simple refund (1)
	 */
	public function actionRefund($id, $ticket = 0) {
		if($payment = Payment::findOne($id)) {
			$newSale = Sequence::nextval('sale');
			if($ticket == 1)
				$credit = new Refund([
					'document_type' => Refund::TYPE_REFUND,
					'client_id' => $payment->client_id,
					'name' => substr($payment->created_at,0,4).'-'.Sequence::nextval('doc_number'),
					'due_date' => date('Y-m-d H:i:s'),
					'note' => $payment->payment_method.'-'.$payment->sale.'. '.$payment->note,
					'sale' => $newSale,
					'status' => Refund::STATUS_TOPAY,
				]);
			else
				$credit = new Credit([
					'document_type' => Credit::TYPE_CREDIT,
					'client_id' => $payment->client_id,
					'name' => substr($payment->created_at,0,4).'-'.Sequence::nextval('credit_number'),
					'due_date' => date('Y-m-d H:i:s'),
					'note' => $payment->payment_method.'-'.$payment->sale.'. '.$payment->note,
					'sale' => $newSale,
					'status' => Credit::STATUS_TOPAY,
				]);
			$credit->save();
			$credit->refresh();
			$credit_item = Item::findOne(['reference' => Item::TYPE_CREDIT]);
			$creditLine = new DocumentLine([
				'document_id' => $credit->id,
				'item_id' => $credit_item->id,
				'quantity' => 1,
				'unit_price' => 0,
				'vat' => $credit_item->taux_de_tva,
				'extra_type' => 'REBATE_AMOUNT',
				'extra_amount' => $payment->amount,
				'due_date' => $credit->due_date,
			]);
			$creditLine->updatePrice(); // adjust htva/tvac from extra's
			$creditLine->save();
			$credit->updatePrice();
			$credit->save();
			$payment->delete();
			Yii::$app->session->setFlash('success', Yii::t('store', 'Credit note created.'));
			return $this->redirect(Url::to(['/order/document/view', 'id' => $credit->id]));
		}
		Yii::$app->session->setFlash('error', Yii::t('store', 'Credit note not created.').'='.$ticket);
		return $this->redirect(Yii::$app->request->referrer);
	}

}
