<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class Refund extends Document
{
	const TYPE = 'REFUND';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_REFUND, 'Credit::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_REFUND]);
    }


	/**
	 * Returns amount due.
	 *
	 * @return number Amount due.
	 */
	public function isPaid() {
		return $this->getBalance() > -Document::PAYMENT_LIMIT;
	}


	/** IMPORTANT: Calls to this method should be framed in a transacation
	 */
	public function addPayment($account, $amount_gross, $method, $note = null) {
		$payment = null;
		$extra = null;
		$new_payment = true;

		Yii::trace('ENTERING='.$this->document_type.' '.$this->name, 'Refund::addPayment');
		
		$amount = round($amount_gross, 2);
		if($amount == 0) return;		

		$ok = true;
		$due = round($this->getBalance(), 2);
		$cash = null;
		
		if($method == 'CASH') {
			$cash = new Cash([
				'document_id' => $this->id,
				'sale' => $this->sale,
				'amount' => $amount,
				'payment_date' => date('Y-m-d H:i:s'),
			]);
			$cash->save();
			$cash->refresh();
		}

		if($amount >= $due) {
			$payment = new Payment([
				'sale' => $this->sale,
				'client_id' => $this->client_id,
				'payment_method' => $method,
				'amount' => $amount,
				'status' => Payment::STATUS_PAID,
				'cash_id' => $cash ? $cash->id : null,
				'account_id' => $account ? $account->id : null,
			]);
		} else { // paid too much, split payment in amount due and surplus
			// 1. record the payment
			$payment = new Payment([
				'sale' => $this->sale,
				'client_id' => $this->client_id,
				'payment_method' => $method,
				'amount' => $due,
				'status' => Payment::STATUS_PAID,
				'cash_id' => $cash ? $cash->id : null,
				'account_id' => $account ? $account->id : null,
			]);
			// 2. record an extra payment in status OPEN
			$surplus = $due + $amount;
			$extra = new Payment([
				'sale' => Sequence::nextval('sale'),
				'client_id' => $this->client_id,
				'payment_method' => $method,
				'amount' => $surplus,
				'note' => 'Extra payment for '.$this->name,
				'status' => Payment::STATUS_OPEN,
				'cash_id' => $cash ? $cash->id : null,
				'account_id' => $account ? $account->id : null,
			]);

			if($method == 'CASH')
				Yii::$app->session->setFlash('info', Yii::t('store', 'You must reimburse {0}€.', $surplus));
			else
				Yii::$app->session->setFlash('info', Yii::t('store', 'Bill paid. Customer left with {0}€ credit.', $surplus));
		}

		Yii::$app->session->setFlash('success', Yii::t('store', 'Payment recorded.'));

		if($note && $payment)
			$payment->note = $note;
			
		if($ok && $payment && $new_payment) {
			$ok = $payment->save();
			History::record($payment, 'ADD', 'Payment added for '.$this->name, true, null);
		}
		if($ok && $extra) {
			$ok = $extra->save();
			History::record($payment, 'ADD', 'Extra credit added: '.$extra->amount, true, null);
		}
		if($ok)
			$this->setStatus(self::STATUS_TOPAY); // will close if necessary (i.e. if isPaid == true)

		Yii::trace('EXITING='.$this->document_type.' '.$this->name, 'Refund::addPayment');

		return $ok;
	}



    /**
     * @inheritdoc
     */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$actions = [];
		switch($this->status) {
			case $this::STATUS_OPEN:
				$actions[] = '{refund}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = '{label:closed}';
				break;
		}
		return implode(' ', $actions) . ' ' . parent::getActions();
	}

}