<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Base class for all things common to bid, order, and bill
 *
 */
class Document extends _Document
{
	const TYPE = 'DOCUMENT';

	/** Order "type" */
	/** Devis */
	const TYPE_BID = 'BID';
	/** Commande */
	const TYPE_ORDER = 'ORDER';
	/** Facture */
	const TYPE_BILL = 'BILL';
	/** Note de crédit */
	const TYPE_CREDIT = 'CREDIT';
	/** Bon de livraison, is a virtual type: an order with bom_bool=true */
	const TYPE_BOM = 'BOM';
	/** Ticket de caisse */
	const TYPE_TICKET = 'TICKET';
	/** Ticket de caisse */
	const TYPE_REFUND = 'REFUND';
	
	
	/** */
	const DATE_TODAY = 0;
	const DATE_TOMORROW = 1;
	const DATE_NEXT = 2;
	const DATE_NEXT_WEEK = 7;
	const DATE_LATE = -1;
	
	
	/** Document status */
	const STATUS_CREATED = 'CREATED';	
	/** */
	const STATUS_OPEN = 'OPEN';
	/** */
	const STATUS_TODO = 'TODO';
	/** */
	const STATUS_BUSY = 'BUSY';
	/** */
	const STATUS_DONE = 'DONE';
	/** */
	const STATUS_NOTIFY = 'NOTIFY';
	/** */
	const STATUS_TOPAY = 'TOPAY';
	/** */
	const STATUS_PAID = 'PAID';
	/** */
	const STATUS_SOLDE = 'SOLDE';
	/** */
	const STATUS_CANCELLED = 'CANCELLED';
	/** */
	const STATUS_CLOSED = 'CLOSED';
	/** */
	const STATUS_WARN = 'WARN';

	/** */
	const EMAIL_PREFIX = 'EMAIL-';

	/** */
	const PAYMENT_LIMIT = 0.01;
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
                'userstamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                        ],
                        'value' => function() { return isset(Yii::$app->user) ? Yii::$app->user->id : null; },
                ],
        ];
    }

	/**
	 * find a document instance and returns it property typed.
     *
     * @return app\models\{Document,Bid,Order,Bill} the document
	 */
	public static function findDocument($id) {
		$model = Document::findOne($id);
		if($model)
			switch($model->document_type) {
				case Document::TYPE_BID:	return Bid::findOne($id);	break;
				case Document::TYPE_ORDER:	return Order::findOne($id);	break;
				case Document::TYPE_BILL:	return Bill::findOne($id);	break;
				case Document::TYPE_CREDIT:	return Credit::findOne($id);break;
				case Document::TYPE_TICKET:	return Ticket::findOne($id);break;
				case Document::TYPE_REFUND:	return Refund::findOne($id);break;
			}
		return null;
	}
	
	protected function newCopy($new_type = null) {
		switch($new_type ? $new_type : $this->document_type) {
			case Document::TYPE_BID:	return new Bid($this->attributes);	break;
			case Document::TYPE_ORDER:	return new Order($this->attributes);	break;
			case Document::TYPE_BILL:	return new Bill($this->attributes);	break;
			case Document::TYPE_CREDIT:	return new Credit($this->attributes);break;
			case Document::TYPE_TICKET:	return new Ticket($this->attributes);break;
			case Document::TYPE_REFUND:	return new Refund($this->attributes);break;
		}
		return null;
	}
	
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['sale' => 'sale']);
    }

	public function getPrepaid($today = false) {
		if($today) {
			$date_from = date('Y-m-d 00:00:00', strtotime('today'));
			$date_to = str_replace($date_from, '00:00:00', '23:59:59');
			$ret = Payment::find()->andWhere(['sale' => $this->sale])
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to])
							->sum('amount');	
		} else
			$ret = Payment::find()->where(['sale' => $this->sale])->sum('amount');
		return $ret ? $ret : 0;
	}
		

	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @param string	$type	Document type
	 * @param boolean	$plural	Whether to return plural form. Default false (returns singular).
	 *
	 * @return string	Label for document type
	 */
	public static function getTypeLabel($type, $plural = false) {
		switch($type) {
			case Document::TYPE_BID:
				$doc_singular = Yii::t('store', 'Bid');
				$doc_plural = Yii::t('store', 'Bids');
				break;
			case Document::TYPE_BILL:
				$doc_singular = Yii::t('store', 'Bill');
				$doc_plural = Yii::t('store', 'Bills');
				break;
			case Document::TYPE_CREDIT:
				$doc_singular = Yii::t('store', 'Credit note');
				$doc_plural = Yii::t('store', 'Credit notes');
				break;
			case Document::TYPE_ORDER:
				$doc_singular = Yii::t('store', 'Order');
				$doc_plural = Yii::t('store', 'Orders');
				break;
			case Document::TYPE_BOM:
				$doc_singular = Yii::t('store', 'Bill of Material');
				$doc_plural = Yii::t('store', 'Bills of Material');
				break;
			case Document::TYPE_TICKET:
				$doc_singular = Yii::t('store', 'Sales Ticket');
				$doc_plural = Yii::t('store', 'Sales Tickets');
				break;
			case Document::TYPE_REFUND:
				$doc_singular = Yii::t('store', 'Refund');
				$doc_plural = Yii::t('store', 'Refunds');
				break;
			default:
				$doc_singular = Yii::t('store', 'Document');
				$doc_plural = Yii::t('store', 'Documents');
				break;
		}
		return $plural ? $doc_plural : $doc_singular;
	}

	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @return array()
	 */
	public static function getStatuses() {
		return [
			self::STATUS_BUSY => Yii::t('store', self::STATUS_BUSY),
			self::STATUS_CANCELLED => Yii::t('store', self::STATUS_CANCELLED),
			self::STATUS_CLOSED => Yii::t('store', self::STATUS_CLOSED),
			self::STATUS_CREATED => Yii::t('store', self::STATUS_CREATED),
			self::STATUS_SOLDE => Yii::t('store', self::STATUS_SOLDE),
			self::STATUS_DONE => Yii::t('store', self::STATUS_DONE),
			self::STATUS_NOTIFY => Yii::t('store', self::STATUS_NOTIFY),
			self::STATUS_TOPAY => Yii::t('store', self::STATUS_TOPAY),
			self::STATUS_OPEN => Yii::t('store', self::STATUS_OPEN),
			self::STATUS_PAID => Yii::t('store', self::STATUS_PAID),
			self::STATUS_TODO => Yii::t('store', self::STATUS_TODO),
			self::STATUS_WARN => Yii::t('store', self::STATUS_WARN),
		];
	}
	
	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @return array()
	 */
	public static function getDocumentTypes() {
		return [
			self::TYPE_BID => Yii::t('store', self::TYPE_BID),
			self::TYPE_ORDER => Yii::t('store', self::TYPE_ORDER),
			self::TYPE_BILL => Yii::t('store', self::TYPE_BILL),
			self::TYPE_CREDIT => Yii::t('store', self::TYPE_CREDIT),
			self::TYPE_TICKET => Yii::t('store', self::TYPE_TICKET),
		];
	}
	
	/**
	 * returns associative array of status, color for all possible status values
	 *
	 * @return array()
	 */
	public static function getStatusColors() {
		// default  primary  success  info  warning  danger
		return [
			self::STATUS_BUSY => 'success',
			self::STATUS_CANCELLED => 'warning',
			self::STATUS_CLOSED => 'success',
			self::STATUS_CREATED => 'success',
			self::STATUS_SOLDE => 'primary',
			self::STATUS_DONE => 'success',
			self::STATUS_NOTIFY => 'primary',
			self::STATUS_TOPAY => 'info',
			self::STATUS_OPEN => 'primary',
			self::STATUS_PAID => 'success',
			self::STATUS_TODO => 'primary',
			self::STATUS_WARN => 'warning',
		];
	}
	
	
	/**
	 * Checks whether a document owns payments and no other document with same sale id owns it.
	 *
	 *	@return boolean
	 */
	public function soloOwnsPayments() {
		if ($this->getPayments()->exists())
			return Document::find()
				->andWhere(['sale'=>$this->sale])
				->andWhere(['!=', 'id', $this->id])->exists();
		return false;
	}
	/**
	 * deleteCascade all its dependent child elements and delete the document
	 */
	public function deleteCascade() {
		/** Delete associated work */
		foreach($this->getWorks()->each() as $w)
			$w->deleteCascade();

		/** Delete order lines */
		foreach($this->getDocumentLines()->each() as $ol)
			$ol->deleteCascade();

		/** Delete order lines */
		foreach($this->getPdfs()->each() as $pdf)
			$pdf->deleteCascade();

		/** Delete payments */

		// if yes, we cannot delete the payment, they belong to the other doc with same sale id
		if(!$this->soloOwnsPayments())
			foreach($this->getPayments()->each() as $p)
				$p->delete();

		$this->delete();
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function hasRebate() {
		$item = Item::findOne(['reference' => Item::TYPE_REBATE]);
		return $item ? DocumentLine::find()->where(['document_id' => $this->id, 'item_id' => $item->id])->count() > 0 : false;
	}

	/**
	 * update price of an order by summing document line prices
	 * set the value value of order-level rebate/supplement line if any.
	 * update total order price.
	 */
	public function updatePrice($do_global_rebate = true) {
		$this->price_htva = 0;
		$this->price_tvac = 0;
		$rebate_line = null;

		foreach($this->getDocumentLines()->each() as $ol) { // gross +/- extra
			if($ol->item->reference === Item::TYPE_REBATE)  // global rebate line
				$rebate_line = $ol;
			else {
				$ol_price_htva = $ol->price_htva + ( isset($ol->extra_htva) ? $ol->extra_htva : 0 );
				$this->price_htva += $ol_price_htva;
				$this->price_tvac += $ol_price_htva * (1 + ($ol->vat / 100));
			}
		}

		// apply global rebate or supplement
		if($rebate_line != null) {
			if ($do_global_rebate) {
				//Yii::trace('Has rebate line '.$rebate_line->id, 'Document::updatePrice');
				if(isset($rebate_line->extra_type) && ($rebate_line->extra_type != '')) {
					//Yii::trace('Has rebate type '.$rebate_line->extra_type, 'Document::updatePrice');
					if(isset($rebate_line->extra_amount) && ($rebate_line->extra_amount > 0)) {
						//Yii::trace('Has rebate amount '.$rebate_line->extra_amount, 'Document::updatePrice');
						$amount = strpos($rebate_line->extra_type, "PERCENT") > -1 ? $this->price_htva * ($rebate_line->extra_amount/100) : $rebate_line->extra_amount;
						$asigne = strpos($rebate_line->extra_type, "SUPPLEMENT_") > -1 ? 1 : -1;
						$rebate_line->price_htva = 0;											// global rebate HTVA is in EXTRA, not in line price
						$rebate_line->extra_htva = round( $asigne * $amount, 2 );				// global rebate HTVA
						$rebate_line->price_tvac = round( $rebate_line->price_htva * 1.21 );	// global rebate TVAC
						$rebate_line->save();
						// re-ajust global order sums
						$this->price_htva += $rebate_line->extra_htva;
						$this->price_tvac += ( $rebate_line->extra_htva * 1.21 );
					}
				}
			} else { // no recalculation, but still add it to order totals
				$this->price_htva += $rebate_line->extra_htva;
				$this->price_tvac += ( $rebate_line->extra_htva * 1.21 );
			}
		}

		//Yii::trace('total '.$this->price_htva, 'Document::updatePrice');
		$this->price_htva = round( $this->price_htva , 2);
		// price TVAC is rounded to nearest 0.05€ (5 c)
		$this->price_tvac = round( round($this->price_tvac * 2, 1) / 2, 2);

		$this->save();
	}


	/** 
	 * Update status and reports to parent Work model
	 */
	public function setStatus($status) {
		$this->status = $status;
		$this->save();
		$this->statusUpdated();
	}


	/**
	 * Update status of document and triggers proper actions.
	 */
	protected function statusUpdated() {
	}


	public function addPayment($amount_gross, $method, $note = null) {
		$payment = null;
		$extra = null;

		Yii::trace('ENTERING='.$this->document_type.' '.$this->name, 'AccountController::addPayment');
		
		$amount = round($amount_gross, 2);
		if($amount == 0) return;		

		$ok = true;
		$due = round($this->getBalance(), 2);
		
		if($method == 'CASH') {
			$payment = new Payment([
				'sale' => $this->sale,
				'client_id' => $this->client_id,
				'payment_method' => $method,
				'amount' => $amount,
				'status' => Payment::STATUS_PAID,
			]);
			$cash = new Cash([
				'document_id' => $this->id,
				'sale' => $this->sale,
				'amount' => $amount,
				'payment_date' => date('Y-m-d H:i:s'),
			]);
			$cash->save();
			if($amount > $due)
				Yii::$app->session->setFlash('warning', Yii::t('store', 'You must reimburse {0}€.', $amount - $due));
		} else if ($method != 'CREDIT' && $method != Payment::CLEAR) {
			if($amount <= $due) { // paid enough or less(prepayment)
				$payment = new Payment([
					'sale' => $this->sale,
					'client_id' => $this->client_id,
					'payment_method' => $method,
					'amount' => $amount,
					'status' => Payment::STATUS_PAID,
				]);
				Yii::$app->session->setFlash('success', Yii::t('store', 'Payment recorded.'));
			} else { // paid too much, split payment in amount due and surplus
				// 1. record the payment
				$payment = new Payment([
					'sale' => $this->sale,
					'client_id' => $this->client_id,
					'payment_method' => $method,
					'amount' => $due,
					'status' => Payment::STATUS_PAID,
				]);
				// 2. record an extra payment in status OPEN
				$surplus = $amount - $due;
				$extra = new Payment([
					'sale' => Sequence::nextval('sale'),
					'client_id' => $this->client_id,
					'payment_method' => $method,
					'amount' => $surplus,
					'note' => 'Extra payment for '.$this->name,
					'status' => Payment::STATUS_OPEN,
				]);
				Yii::$app->session->setFlash('success', Yii::t('store', 'Payment recorded.'));
				Yii::$app->session->setFlash('info', Yii::t('store', 'Bill paid. Customer left with {0}€ credit.', $surplus));
			}
		} else if ($method == Payment::CLEAR) { // we just record a payment because it clears an existing credit note
				$payment = new Payment([
					'sale' => $this->sale,
					'client_id' => $this->client_id,
					'payment_method' => $method,
					'amount' => $amount,
					'status' => Payment::STATUS_PAID,
				]);
				Yii::trace('Clearing='.$amount, 'AccountController::addPayment');
		} else { // if($method == 'CREDIT')
			if($amount >= 0) { // client pays with credit he has

				$payment = new Payment([
					'sale' => $this->sale,
					'client_id' => $this->client_id,
					'payment_method' => $method,
					'amount' => $amount,		// Amount may be adjusted below if paid with CREDIT
					'status' => Payment::STATUS_PAID,
				]);

				$creditNotes = Credit::find()
					->andWhere(['client_id' => $this->client_id])
					->andWhere(['status' => [Credit::STATUS_TOPAY,Credit::STATUS_SOLDE]])
					->orderBy('created_at');

				$needed = $amount;
				$total_available = 0;
				Yii::trace('Needed='.$needed, 'AccountController::addPayment');
				foreach($creditNotes->each() as $cn) {
					if($needed > 0) {
						$available = abs($cn->getBalance());
						$total_available += $available;
						$comment = Yii::t('store', 'Payment of {0} {1}.', [Yii::t('store', $this->document_type),$this->name]);
						Yii::trace('Available='.$available.' with '.$cn->id, 'Document::addPayment');
						if($needed <= $available) { // no problem, we widthdraw from credit note
							Yii::trace('Found='.$needed, 'Document::addPayment');
							$cn->addPayment(-$needed, Payment::CLEAR,$comment);
							$needed = 0;
						} else {
							Yii::trace('Clear='.$available, 'Document::addPayment');
							$cn->addPayment(-$available, Payment::CLEAR,$comment); // this credit note is exhausted
							$needed -= $available;
						}
						Yii::trace('Still need='.$needed, 'Document::addPayment');
					} else
						$total_available += $available;
				}
				Yii::trace('Total credit available before='.$total_available, 'Document::addPayment');
				$total_available -= $amount;
				Yii::trace('Total credit available after='.$total_available, 'Document::addPayment');
				Yii::trace('Final need='.$needed, 'Document::addPayment');

				if($needed > 0) { // still some amount to pay and credit notes exhausted, this bill is not completed paid.
					$payment->amount = $amount - $needed;
					Yii::$app->session->setFlash('warning', Yii::t('store', 'Amount is too large to balance all credit notes. Customer left with {0}€ to pay.', round($needed, 2)));
				} else if ($total_available > 0) {
					Yii::$app->session->setFlash('info', Yii::t('store', 'Bill paid with credits. Customer left with {0}€ credit.', round($total_available, 2)));
				}

			} else { // $capture->amount is negative, and we use CREDIT
				/*
				$clear = new Payment([
					'sale' => $this->sale,
					'client_id' => $this->client_id,
					'payment_method' => Payment::CLEAR,
					'amount' => $amount,		// Amount may be adjusted below if paid with CREDIT
					'status' => Payment::STATUS_PAID,
				]);
				$clear->save();
				*/
				$ok = false;
				Yii::$app->session->setFlash('error', Yii::t('store', 'Negative credit charge not handled yet. Pierre 25/01/2015.'));
			}
		}
		
		if($note && $payment)
			$payment->note = $note;
			
		if($ok && $payment)
			$ok = $payment->save();
		if($ok && $extra)
			$ok = $extra->save();
		if($ok)
			$this->updatePaymentStatus();

		Yii::trace('EXITING='.$this->document_type.' '.$this->name, 'AccountController::addPayment');

		return $ok;
	}


	/**
	 * Returns amount due.
	 *
	 * @return number Amount due.
	 */
	public function getBalance() {
		return round($this->getTotal() - $this->getPrepaid(), 2);
	}
	

	/**
	 * Returns total.
	 *
	 * @return number Total amount of this document.
	 */
	public function getTotal() {
		return $this->vat_bool ? $this->price_htva : $this->price_tvac;
	}


	/**
	 * Returns amount due.
	 *
	 * @return number Amount due.
	 */
	public function isPaid() {
		return $this->getBalance() < Document::PAYMENT_LIMIT;
	}
		
	/**
	 * Update payment status of document and triggers proper actions.
	 */
	public function updatePaymentStatus() {
		Yii::trace('isPaid='.($this->isPaid()?'T':'F'), 'Document::updatePaymentStatus');
		if(!$this->isBusy())
			$this->setStatus($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY);
	}


	public function isBusy() {
		return $this->status == self::STATUS_TODO || $this->status == self::STATUS_BUSY;
	}
	
	
	public function latestPayment() {
		$last = $this->getPayments()->orderBy('created_at desc')->one();
		return $last ? $last->amount : 0;
	}


	public function getDelay($which, $category = false) {
		$refdate = ($which == 'created') ? new \DateTime($this->created_at) : new \DateTime($this->updated_at);
		$cnt = $refdate->diff(new \DateTime())->days;
		if($category) {
		     if($cnt <= Parameter::getIntegerValue('delay', 'late', 30)) return 0;
		else if($cnt <= Parameter::getIntegerValue('delay', 'verylate', 60)) return 1;
		else if($cnt <= Parameter::getIntegerValue('delay', 'veryverylate', 90)) return 2;
		else return 3;			
		}
		else
			return $cnt;
	}

	/**
	 * creates a copy of a document object with a *copy* of all its dependent objects (documentlines, etc.)
	 *
     * @return app\models\Document the copy
	 */
	public function deepCopy($new_type = null) {
		$copy = $this->newCopy($new_type);
		if($new_type) $copy->document_type = $new_type;	// the copy has overwritten the requested type
		$copy->id = null;								// we reset the id to get a new one
		$copy->save();
		foreach($this->getDocumentLines()->each() as $sub)
			$sub->deepCopy($copy->id);
		return $copy;
	}
	
    /**
     * convert deep copy a bid (resp. an order) to make an order (resp. a bill) and then closes the orignal document.
     * Has no effect on bill. If a following document already exists, it returns it rather than create a new one.
     * Therefore, there should only be one bid, one order, and one bill linked together at most.
     * When transforming an order into a bill, places the bill onto send mode.
     * Deep copy means that child elements are also copied (order lines, order line details).
     *
     * @return app\models\Document the copy
     */
	public function convert($ticket = false) {
		return null;
	}
	
	public function convert_old() {
		if($this->document_type == self::TYPE_BILL) // no next operation after billing
			return null;

		$next_type = $this->document_type == self::TYPE_BID ? self::TYPE_ORDER :
						$this->document_type == self::TYPE_ORDER ? self::TYPE_BILL : null;

     	/** if a following document already exists, it returns it rather than create a new one. */
		if( $existing_next = $this->find()->andWhere(['parent_id' => $this->id])->andWhere(['document_type' => $next_type])->one() )
			return $existing_next;

		$new_type = ($this->document_type == self::TYPE_BID) ? self::TYPE_ORDER : self::TYPE_BILL;
		$copy = $this->deepCopy($new_type);
		$copy->parent_id = $this->id;
		
		if($copy->document_type == self::TYPE_BILL) // get a new official bill number
			$copy->name = substr($this->due_date,0,4).'-'.Sequence::nextval('bill_number'); // $this->due_date or $copy->due_date?
		
		$copy->status = self::STATUS_OPEN;
		$copy->save();
		
		if($copy->document_type == self::TYPE_ORDER && Parameter::isTrue('application', 'auto_submit_work')) {
			Yii::trace('auto_submit_work for '.$copy->id, 'Document::convert');
			$work = $copy->createWork();
		} if($copy->document_type == self::TYPE_BILL && Parameter::isTrue('application', 'auto_send_bill')) {
			Yii::trace('auto_send_bill for '.$copy->id, 'Document::convert');
			$copy->send();
		}

		$this->status = self::STATUS_CLOSED;
		$this->save();	

		return $copy;
	}
	
	
	public function numberLines() {
		$pos = 1;
		foreach($this->getDocumentLines()->each() as $dl) {
			$dl->position = $pos++;
			$dl->save();
		}
	}
	
	
	public function getLineCount() {
		$cnt = $this->getDocumentLines()->count();
		return $this->hasRebate() ? $cnt - 1 : $cnt;
	}
	/**
	 *
	 * Note: $table introduced when joining several tables with due_date (ex. document and work)
	 */
	public static function getDateClause($id, $table = null) {
		$column = $table ? $table.'.due_date' : 'due_date';
		$dayofweek = date('N'); // Mon=1, Sun=7
		$today = date('Y-m-d');
		Yii::trace('today='.$today.', dayofweek='.$dayofweek);
		switch(intval($id)) {
			case self::DATE_TOMORROW: // next open day
				$nextday = ($dayofweek > 4) ? 3 : 1;
				$day = date('Y-m-d', strtotime('now + '.$nextday.' days'));
				$where = [$column => $day];
				break;
			case self::DATE_NEXT: // 2 days from now, but open day
				$nextday = ($dayofweek > 3) ? 4 : 2;
				$day = date('Y-m-d', strtotime('now + '.$nextday.' days'));
				$where = [$column => $day];
				break;
			case self::DATE_NEXT_WEEK:
				$day = date('Y-m-d', strtotime('now + 7 days'));
				$where = ['and', ['<', $column, $day], ['>', $column, $today]];
				break;
			case self::DATE_LATE:
				$day = date('Y-m-d', strtotime('today'));
				$where = ['<', $column, $day];
				break;
			case self::DATE_TODAY:
				$day = date('Y-m-d', strtotime('today'));
				$where = [$column => $day];
				break;
			default:
				$where = ['is not', $column, null];
				break;
		}
		return $where;
	}
	
	/**
	 *
	 */
	public static function getDateWords($id = null) {
		switch(intval($id)) {
			case 1: $title = 'for tomorrow'; break;
			case 2: $title = 'for after tomorrow'; break;
			case 7: $title = 'for next week'; break;
			case -1: $title = 'that are Late'; break;
			default: $title = 'for today'; break;
		}
		return $title;
	}

	/**
	 * @return boolean whether at least one of its order line has a order line detail element
	 */
	public function hasDetail() {
		$hasDetail = false;
		foreach($this->getDocumentLines()->each() as $ol)
			if($ol->hasDetail()) $hasDetail = true;
		return $hasDetail;
	}
	
	/**
	 * @return boolean whether at least one of its order line has picture attached to it
	 */
	public function hasPicture() {
		$hasPicture = false;
		foreach($this->getDocumentLines()->each() as $ol)
			if($ol->hasPicture()) $hasPicture = true;
		return $hasPicture;
	}
	
	/**
	 * Determine if document can still be edited.
	 * Only status can sometimes be changed through strict procedures, even if document cannot be edited.
	 *
	 * @return boolean Whether document can still be modified
	 */
	public function canModify() {
		return ($this->status != Document::STATUS_CLOSED);
	}

	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getButton($template, $icon, $text) {
		return str_replace(
			'{icon}', '<span class="glyphicon glyphicon-'.$icon.'"></span> ', str_replace(
				'{text}', Yii::t('store', $text), $template
			)
		);
	}
	
	/**
	 * Generates document action button template '{action}...' to be used by DocumentActionColumn generator.
	 * Generation takes into account document type and status to generate a list of valid actions.
	 *
	 * @return string Action column button template.
	 */
	public function getActions() {
		return '{view} {print}';
	}

	/**
	 * Generates colored labels for Document status
	 *
	 * @return string HTML fragment
	 */
	public function getStatusColor() {
		$color = $this->getStatusColors();
		return $color[$this->status];
	}
	
	/**
	 * Generates colored labels for Document. Color depends on document status.
	 *
	 * @return string HTML fragment
	 */
	public function getLabel($str) {
		return '<span class="label label-'.$this->getStatusColor().'">'.Yii::t('store', $str).'</span>';
	}

	/**
	 * Generates colored labels for Document status
	 *
	 * @return string HTML fragment
	 */
	public function getStatusLabel($colored = false) {
		return $colored ? $this->getLabel($this->status) : Yii::t('store', $this->status);
	}

	/**
	 * Generates belgian Structurer Communication (XXX/XXXXX/XXYY) from supplied number.
	 *
	 * @return string Structurer Communication
	 */
	public static function commStruct($s=0) {
        $d=sprintf("%010s",preg_replace("/[^0-9]/", "", $s)); 
        $modulo=(bcmod($s,97)==0?97:bcmod($s,97)); 
        return sprintf("%s/%s/%s%02d",substr($d,0,3),substr($d,3,4),substr($d,7,3),$modulo); 
	}
	
	/**
	 * Extract sequence number of document name.
	 *
	 * @return number Sequence number of document if structured as STRING-00000.
	 */
	public function getNumberPart() {
		$p = strrpos($this->name, '-');
		return $p > 0 ? substr($this->name, $p + 1, strlen($this->name) - $p + -1) : 0;
	}

}