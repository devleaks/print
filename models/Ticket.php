<?php

namespace app\models;

use Yii;

class Ticket extends Order
{
	const TYPE = 'TICKET';
	
    /**
     * @inheritdoc
     */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_TICKET, 'Ticket::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_TICKET]);
    }

	/**
	 * @inheritdoc
	 */
	protected function statusUpdated() {
		if($this->status == self::STATUS_DONE) {
			$this->status = $this->updatePaymentStatus();
			$this->save();
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function updatePaymentStatus() {
		if(!$this->isBusy()) {
			return $this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY;
		} // otherwise, we leave the status as it is
		return $this->status;
	}

    /**
     * @inheritdoc
     * Note: If we convert a sale ticket to a bill, we change its type to ORDER
	 */
	public function convert($ticket = false) { // convert ORDER into BILL
		$transaction = Yii::$app->db->beginTransaction();

		if(!$this->client->isComptoir()) {

			if($this->hasPayments()) {
				// re-create VC
				$amount = $this->getAmount();
				
				// Create a copy of original for archive.
				$ticket = $this->newCopy();
				$ticket->id = null;
				$ticket->sale = Sequence::nextval('sale');
				$ticket->name .= '-FACTURE';
				$ticket->setStatus(Document::STATUS_CANCELLED);
				$ticket->save();

				$this->document_type = self::TYPE_ORDER;
				$this->save();
				
				// Create a reimbursement that will cancel the ticket
				$o = Parameter::getTextValue('application', Refund::TYPE_REFUND, '-');
				$reimbursment = new Refund([
					'document_type' => Refund::TYPE_REFUND,
					'client_id' => $this->client_id,
					'name' => substr($this->created_at,0,4).$o.str_pad(Sequence::nextval('doc_number'), Bill::BILL_NUMBER_LENGTH, "0", STR_PAD_LEFT),
					'due_date' => $this->due_date,
					'note' => 'Remboursement conversion VC->Facture',
					'sale' =>  Sequence::nextval('sale'),
					'status' => Refund::STATUS_TOPAY,
					'reference' => $this->reference,
				]);
				$reimbursment->save();
				$reimbursment->refresh();
				$credit_item = Item::findOne(['reference' => Item::TYPE_REFUND]);
				$model_line = new DocumentLine([
					'document_id' => $reimbursment->id,
					'item_id' => $credit_item->id,
					'quantity' => 1,
					'unit_price' => 0,
					'vat' => $credit_item->taux_de_tva,
					'due_date' => $this->due_date,
					'extra_type' => DocumentLine::EXTRA_REBATE_AMOUNT,
					'extra_htva' => -$amount,
				]);
				$model_line->save();
				$reimbursment->updatePrice();
				$reimbursment->save();

				$account = new Account([
					'client_id' => $this->client_id,
					'payment_method' => Payment::USE_CREDIT,
					'payment_date' => date('Y-m-d H:i:s'),
					'amount' => $amount,
					'status' => $amount > 0 ? 'CREDIT' : 'DEBIT',
					'cash_id' => null,
					'note' => 'Client transfer',
				]);
				// it uses credit, so the money is already in the system, so DO NOT save new Account
				// (Account object only used to add payment but NOT SAVED).
				$ticket->addPayment($account, $amount, Payment::USE_CREDIT, 'VC -> C/F');
				$ticket->status = Document::STATUS_CLOSED;
				$ticket->save();

			} else {
				$this->document_type = self::TYPE_ORDER;
				$this->save();

			}

			$ret = parent::convert($this);
			$transaction->commit();
			return $ret;
		}
		$transaction->rollback();
		return null;
	}


    /**
     * @inheritdoc
	 */
	public function getActions($show_work = false) {
		$actions = [];

		$ret = '';
		$work = $this->getWorks()->one();
		if( $show_work && $work ) $ret .= '<p>'.$work->getTaskIcons(true, true, true).'</p>';

		switch($this->status) {
			case $this::STATUS_CREATED:
				$actions[] = '{edit}';
				$actions[] = '{cancel}';
				break;
			case $this::STATUS_OPEN:
				$actions[] = '{edit}';
				$actions[] = '{submit}';
				$actions[] = '{cancel}';
				break;
			case $this::STATUS_WARN:
				$actions[] = '{warn}';
			case $this::STATUS_TODO:
			case $this::STATUS_BUSY:
				$actions[] = '{cancel}';
				$actions[] = '{bill-ticket}';
				if( $work  ) { // there should always be a work if doc status is TODO or BUSY or WARN
					$actions[] = '{work}';
					$actions[] = '{workterminate}';
				} else
					$actions[] = '{terminate}';
				break;
			case $this::STATUS_DONE:
			case $this::STATUS_TOPAY:
				$actions[] = '{bill-ticket}';
				$actions[] = '{receive}';
				break;
			case $this::STATUS_CANCELLED:
				$actions[] = '{label:cancelled}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = '{label:closed}';
				$actions[] = '{bill-ticket}';
				break;
		}
		return $ret . implode(' ', $actions) . ' {view} {print}';//cannot get parent:: which is Order
	}

}