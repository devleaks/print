<?php

namespace app\models;

use app\components\Blab;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Base class for all things common to bid, order, and bill
 *
 */
class Document extends _Document
{
	use Blab;
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
	const STATUS_CANCELLED = 'CANCELLED';
	/** */
	const STATUS_CLOSED = 'CLOSED';
	/** */
	const STATUS_WARN = 'WARN';

	/** */
	const EMAIL_PREFIX = 'EMAIL-';

	/** */
	const PAYMENT_LIMIT = 0.01;


	/** Variables added for search models (DocumentSearch, BillSearch, etc.) */
	public $created_at_range;
	public $updated_at_range;
	public $duedate_range;
	protected $blab;

	public $client_name;


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
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
        	[['email'], 'email'],
		]);
	}
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        	'client_id' => Yii::t('store', 'Client'),
        	'client_name' => Yii::t('store', 'Client'),
        	'created_at_range' => Yii::t('store', 'Created At'),
        	'updated_at_range' => Yii::t('store', 'Updated At'),
        	'duedate_range' => Yii::t('store', 'Due Date'),
        ]);
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
	
	
	public static function findBySale($sale) {
		if($document = Document::find()->andWhere(['sale' => $sale])->orderBy('created_at desc')->one()) {
			return self::findDocument($document->id);
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
	
	
	public static function append($to, $src, $sep, $max = 160) {
		return (strlen($src)+strlen($sep)+strlen($to)) < $max ? ($to ? $to.$sep.$src : $src) : $to ;
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['sale' => 'sale']);
    }

	/**
	 *
	 */
    public function getAmount($display = false)	{
		if($display) {
			$a = Yii::$app->formatter->asCurrency($this->getAmount());
			return $this->vat_bool ?  '<sup>HTVA</sup> '. $a : $a;
		}		
		return $this->vat_bool ? $this->price_htva : $this->price_tvac;
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
	public function getPictures()
    {
        return $this->hasMany(Picture::className(), ['document_line_id' => 'id'])
            ->viaTable('document_line', ['document_id' => 'id']);
    }

	public function getPrepaid($today = false) {
		if($today) {
			$date_from = date('Y-m-d 00:00:00', strtotime('today'));
			$date_to = str_replace($date_from, '00:00:00', '23:59:59'); // date('Y-m-d 00:00:00', strtotime('tomorrow'));
			$ret = Payment::find()->andWhere(['sale' => $this->sale])
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to])
							->sum('amount');	
		} else
			$ret = Payment::find()->where(['sale' => $this->sale])->sum('amount');
		Yii::trace('ret='.$ret ? $ret : 0, 'Document::getPrepaid');
		return $ret ? $ret : 0;
	}
		

	public static function isValidStatus($status) {
		return in_array($status, array_keys(self::getStatuses()));
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
			self::STATUS_CREATED => Yii::t('store', self::STATUS_CREATED),
			self::STATUS_OPEN => Yii::t('store', self::STATUS_OPEN),
			self::STATUS_TODO => Yii::t('store', self::STATUS_TODO),
			self::STATUS_BUSY => Yii::t('store', self::STATUS_BUSY),
			self::STATUS_WARN => Yii::t('store', self::STATUS_WARN),
			self::STATUS_DONE => Yii::t('store', self::STATUS_DONE),
			self::STATUS_NOTIFY => Yii::t('store', self::STATUS_NOTIFY),
			self::STATUS_TOPAY => Yii::t('store', self::STATUS_TOPAY),
			self::STATUS_CLOSED => Yii::t('store', self::STATUS_CLOSED),
			self::STATUS_CANCELLED => Yii::t('store', self::STATUS_CANCELLED),
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
			self::STATUS_DONE => 'success',
			self::STATUS_NOTIFY => 'primary',
			self::STATUS_TOPAY => 'info',
			self::STATUS_OPEN => 'primary',
			self::STATUS_TODO => 'primary',
			self::STATUS_WARN => 'warning',
		];
	}
	
	
	public function getRelatedReference() {
		return $this->parent ? $this->parent->name : '';
	}
	
	
	/**
	 * Checks whether a document owns payments and no other document with same sale id owns it.
	 *
	 *	@return boolean
	 */
	public function soloOwnsPayments() {
		if ($this->getPayments()->exists())
			return ! (Document::find()
				->andWhere(['sale'=>$this->sale])
				->andWhere(['!=', 'id', $this->id])->exists());
		return false;
	}


	/**
	 * deleteCascade all its dependent child elements and delete the document
	 */
	public function deleteCascade() {
		if(! $this->soloOwnsPayments() ) {

			/** Detach from website order if any */
			if($wso = WebsiteOrder::findOne(['document_id' => $this->id])) {
				$wso->document_id = null;
				$wso->status = WebsiteOrder::STATUS_OPEN;
				$wso->save();
			}			
			
			/** Delete associated work */
			foreach($this->getWorks()->each() as $w)
				$w->deleteCascade();

			/** Delete order lines */
			foreach($this->getDocumentLines()->each() as $ol)
				$ol->deleteCascade();

			/** Delete order lines */
			foreach($this->getPdfs()->each() as $pdf)
				$pdf->deleteCascade();

			return $this->delete();
		}
		return false;
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
	 *
	 * @return boolean
	 */
	public function hasPayments() {
		return $this->getPayments()->exists();
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
				$this->price_tvac += round($ol_price_htva * (1 + ($ol->vat / 100)), 2);
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
						$this->price_tvac += round( $rebate_line->extra_htva * 1.21 , 2);
					}
				}
			} else { // no recalculation, but still add it to order totals
				$this->price_htva += $rebate_line->extra_htva;
				$this->price_tvac += round( $rebate_line->extra_htva * 1.21 , 2);
			}
		}

		//Yii::trace('total '.$this->price_htva, 'Document::updatePrice');
		$this->price_htva = round( $this->price_htva , 2);
		// price TVAC is rounded to nearest 0.05€ (5 c)
		// $this->price_tvac = round( round($this->price_tvac * 2, 1) / 2, 2);
		$this->price_tvac = round( $this->price_tvac , 2);

		$this->save();
	}


	/** 
	 * Update status and reports to parent Work model
	 */
	public function setStatus($newstatus) {
		if(!$this->isValidStatus($newstatus))
			return;
		Yii::trace('Entering current status = '.$this->status.', request to set '.$newstatus.'.', 'Document::setStatus');

		switch($newstatus) {
			case self::STATUS_DONE:
				Yii::trace('Request to set DONE.', 'Document::setStatus');
				if($this->getNotificationEmail() != '') {
					Yii::trace('has email, set to NOTIFY.', 'Document::setStatus');
					$this->status = self::STATUS_NOTIFY;
				} else {
					$this->status = $newstatus;
					$s = $this->updatePaymentStatus();
					Yii::trace('has no email, set to '.$s.'.', 'Document::setStatus');
					$this->status = $s;
				}
				break;
			case self::STATUS_TOPAY: 
				Yii::trace('Request to set TOPAY.', 'Document::setStatus');
				if($work = $this->getWorks()->one()) {
					if($work->status == Work::STATUS_DONE) { // if work exists and is completed
						$this->status = $this->updatePaymentStatus();
					} else { // document takes status of its associated work
						$this->status = $work->status;
					}
				} else { // there is no work. If document in "TODO" status, we leave it as TODO.
					if(!in_array($this->status,[self::STATUS_TODO, self::STATUS_OPEN, self::STATUS_CREATED])) {
						$this->status = $this->updatePaymentStatus();
					}
				}
				break;
			case self::STATUS_CANCELLED:
				Yii::trace('Request to cancel.', 'Document::setStatus');
				$sale = Sequence::nextval('sale');
				foreach($this->getPayments()->each() as $payment) {
					$payment->sale = $sale;
					$payment->status = Payment::STATUS_OPEN;
					$payment->save();
				}
				$this->status = $newstatus;
				break;
			default:
				Yii::trace('Not special request.', 'Document::setStatus');
				$this->status = $newstatus;
				break;
		}
		$this->save();
		Yii::trace('Saved status = '.$this->status.'.', 'Document::setStatus');
		$this->statusUpdated();
		Yii::trace('After statusUpdated = '.$this->status.'.', 'Document::setStatus');
	}


	/**
	 * Update status of document and triggers proper actions.
	 */
	protected function statusUpdated() {
	}


	/** IMPORTANT: Calls to this method should be framed in a transacation
	 */
	public function addPayment($account, $amount_gross, $method, $note = null) {
		$payment = null;
		$extra = null;
		$new_payment = true;

		Yii::trace('ENTERING='.$this->document_type.' '.$this->name, 'Document::addPayment');
		
		$amount = round($amount_gross, 2);
		if($amount == 0) return;		

		$ok = true;
		$due = round($this->getBalance(), 2);
		$cash = null;
		
		// $client_id = ($this->document_type == self::TYPE_TICKET || $this->document_type == self::TYPE_REFUND) ? Client::auComptoir()->id : $this->client_id;
		$client_id = $this->client_id;
		
		if (!in_array($method, [Payment::USE_CREDIT,Payment::CLEAR])) {

			if($due < 0) { // refund

				Yii::trace('Reimbursement: due='.$due.', amount='.$amount, 'Document::addPayment');
				if($amount >= $due) {
					$payment = new Payment([
						'sale' => $this->sale,
						'client_id' => $client_id,
						'payment_method' => $method,
						'amount' => $amount,
						'status' => Payment::STATUS_PAID,
						'cash_id' => $account->cash_id,
						'account_id' => $account ? $account->id : null,
						'note' => $note,
					]);
					Yii::$app->session->setFlash('success', Yii::t('store', 'Payment recorded.'));
				} else {
					$ok = false;
					Yii::$app->session->setFlash('warning', Yii::t('store', 'Refund amount cannot be larger than amount to reimburse.'));
				}

			} else { // regular payment
				if($amount <= $due) {
					$payment = new Payment([
						'sale' => $this->sale,
						'client_id' => $client_id,
						'payment_method' => $method,
						'amount' => $amount,
						'status' => Payment::STATUS_PAID,
						'cash_id' => $account->cash_id,
						'account_id' => $account ? $account->id : null,
						'note' => $note,
					]);
				} else { // paid too much, split payment in amount due and surplus
					// 1. record the payment
					$payment = new Payment([
						'sale' => $this->sale,
						'client_id' => $client_id,
						'payment_method' => $method,
						'amount' => $due,
						'status' => Payment::STATUS_PAID,
						'cash_id' => $account->cash_id,
						'account_id' => $account ? $account->id : null,
						'note' => $note,
					]);

					// 2. record an extra payment in status OPEN
					$surplus = $amount - $due;
					if($surplus > self::PAYMENT_LIMIT){
						$extra = new Payment([
							'sale' => Sequence::nextval('sale'),
							'client_id' => $client_id,
							'payment_method' => $method,
							'amount' => $surplus,
							'note' => Yii::t('store', 'Extra payment for {0}',$this->name),
							'status' => Payment::STATUS_OPEN,
							'cash_id' => $account->cash_id,
							'account_id' => $account ? $account->id : null,
						]);
						if($method == Payment::CASH)
							Yii::$app->session->setFlash('info', Yii::t('store', 'You must reimburse {0}€.', $surplus));
						else
							Yii::$app->session->setFlash('info', Yii::t('store', 'Bill paid. Customer left with {0}€ credit.', $surplus));
					}

				}
				Yii::$app->session->setFlash('success', Yii::t('store', 'Payment recorded.'));
			}


		} elseif ($method == Payment::CLEAR) { // we just record a payment because it clears an existing credit note

				$payment = new Payment([
					'sale' => $this->sale,
					'client_id' => $client_id,
					'payment_method' => $method,
					'amount' => $amount,
					'status' => Payment::STATUS_PAID,
					'account_id' => $account ? $account->id : null,
					'note' => $note,
				]);
				Yii::trace('Clearing='.$amount, 'Document::addPayment');

		} else { // $method == Payment::USE_CREDIT), client pays with credit he has
			if($amount >= 0) {

				$needed = $amount;
				$total_available = 0;
				Yii::trace('Needed='.$needed, 'Document::addPayment');
				foreach($this->client->getCreditLines() as $credit_line) { // now use (delete/remove) credit lines as they are used
					$available = abs($credit_line->amount);
					$total_available += $available;
					$add_payment = false;
					if($needed > 0) {
						$comment = Yii::t('store', 'Payment of {0} {1}.', [Yii::t('store', $this->document_type),$this->name]);
						Yii::trace('Available='.$available.' with '.$credit_line->ref, 'Document::addPayment');
						if($needed <= $available) { // no problem, we widthdraw from credit note
							Yii::trace('Found='.$needed, 'Document::addPayment');
							$credit_used = $needed;
							$needed = 0;
						} else {
							Yii::trace('Clear='.$available, 'Document::addPayment');
							$credit_used = $available;
							$needed -= $available;
						}
						Yii::trace('...still need='.$needed, 'Document::addPayment');
						$add_payment = $credit_line->useAmount($this, $credit_used, $comment);
					}
					if($add_payment) {
						$account_id = null;
						if($credit_line->source == CreditLine::SOURCE_ACCOUNT) { // we need to carry the account_id to the payment
							if($credit = Payment::findOne($credit_line->ref))
								$account_id = $credit->account_id;
						}
						$payment = new Payment([
							'sale' => $this->sale,
							'client_id' => $client_id,
							'payment_method' => $method,
							'amount' => $credit_used,
							'status' => Payment::STATUS_PAID,
							'account_id' => $account_id,
							'note' => $note,
						]);
						$payment->save();
						History::record($payment, 'ADD', 'Payment added for '.$this->name, true, null);
						Yii::trace('Added payment '.$payment->id.' for credit '.$credit_line->ref, 'Document::addPayment');
					}
				}
				$new_payment = false;

				Yii::trace('Total credit available before='.$total_available, 'Document::addPayment');
				$total_available -= $amount;
				Yii::trace('Total credit available after='.$total_available, 'Document::addPayment');
				Yii::trace('Final need='.$needed, 'Document::addPayment');

				if($needed > 0) { // still some amount to pay and credit notes exhausted, this bill is not completed paid.
					Yii::$app->session->setFlash('warning', Yii::t('store', 'Amount is too large to balance all credit notes. Customer left with {0}€ to pay.', round($needed, 2)));
				} else if ($total_available > 0) {
					Yii::$app->session->setFlash('success', Yii::t('store', 'Bill paid with credits. Customer left with {0}€ credit.', round($total_available, 2)));
				}

			} else {
				$ok = false;
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Negative credit charge not allowed.'));
			}
		}
		
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

		Yii::trace('EXITING='.$this->document_type.' '.$this->name, 'Document::addPayment');

		return $ok;
	}



	/**
	 *	Delete payment from document. Reset status to TOPAY. Set status will check if payment sufficient.
	 *  Do_account = false means multi-document payment and account is not deleted.
	 */
	public function deletePayment($id, $do_account = true) {
		if($payment = Payment::findOne($id)) {
			if($do_account && $payment->partOfMultiplePayment()) {
				Yii::$app->session->setFlash('warning', Yii::t('store', 'Payment was split {0}. Click on link to view all sales.',
					Html::a(Yii::t('store', 'across multiple sales'), ['/accnt/account/view', 'id' => $payment->account_id])));
				return;
			}
			
			$transaction = Yii::$app->db->beginTransaction();
			if($payment->amount < 0) { // cancel refund
				$credit_amount = 0;
				foreach($payment->getPaymentLinks()->each() as $pl) {
					if($credit = $pl->getLinked()->one()) {
						$credit_amount += $credit->amount;
						$credit->status = Payment::STATUS_OPEN;
						$credit->save();
					}
					$pl->delete();
				}
				
				History::record($payment, 'DELETE', 'Credit restored for '.$this->name.'='.$credit_amount, true, null);
				Yii::trace('Created credit for='.$this->name, 'Document::deletePayment');
				Yii::$app->session->setFlash('success', Yii::t('store', 'Payment with credit deleted. {0} updated. Credit amount {0}€ restored.',
							[$credit_amount]));
				
				if($account = Account::findOne($payment->account_id)) {
					if($payment->payment_method == Payment::CASH) {
						if($cash = Cash::findOne($payment->cash_id)) {
							$cash_date = $cash->created_at;
							$payment->delete();
							if(! $cash->getPayments()->exists()) { // are there other payments that depends on this cash? If not, we delete cash as well.
								$cash->delete();
							}
							History::record($payment, 'DELETE', 'Cash payment deleted', true, null);
							if($do_account) $account->deleteWithCash();
							Yii::$app->session->setFlash('success', Yii::t('store', 'Cash payment deleted. {0} updated. You must review cash balance from {1}.',
										[$this->name, Yii::$app->formatter->asDate($cash_date)]));
							Yii::trace('Deleted cash for='.$this->name, 'Document::deletePayment');
						} else {
							Yii::$app->session->setFlash('danger', Yii::t('store', 'Cash payment not deleted because cash entry was not found.'));
							return;
						}
					} else {
						$payment->delete();
						History::record($payment, 'DELETE', 'Payment deleted', true, null);
						if($do_account) $account->deleteWithCash();
						Yii::$app->session->setFlash('success', Yii::t('store', 'Payment deleted. {0} updated.', [$this->name]));
						Yii::trace('Deleted payment for='.$this->name, 'Document::deletePayment');
					}
					$this->setStatus(Document::STATUS_TOPAY);
					$transaction->commit();
					Yii::trace('Status TOPAY for '.$this->name, 'Document::deletePayment');
				} else {
					$transaction->rollback();
					Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not deleted because account entry was not found.'));
					return;
				}
	
			} else { // cancel payment
				
				if($payment->payment_method == Payment::USE_CREDIT) { // used credit, we have to place the credit back, there is no account line for credit
					// OPEN TRANSACTION
					if(empty($payment->account_id)) { // we must restore the previous credit
						$credit = new Payment([
							'sale' => Sequence::nextval('sale'),
							'client_id' => $payment->client_id,
							'payment_method' => Payment::USE_CREDIT,
							'amount' => $payment->amount,
							'note' => Yii::t('store', 'Credit payment cancelled.'),
							'status' => Payment::STATUS_OPEN,
						]);
						$credit->save();
						History::record($payment, 'DELETE', 'Credit added for '.$this->name.'='.$credit->amount, true, null);
						Yii::trace('Created credit for='.$this->name, 'Document::deletePayment');
						Yii::$app->session->setFlash('success', Yii::t('store', 'Payment with credit deleted. {0} updated. Credit amount {0}€ restored.',
									[$credit->amount]));
					}
					$payment->delete();
					History::record($payment, 'DELETE', 'Payment deleted', true, null);
					Yii::trace('Deleted credit for='.$this->name, 'Document::deletePayment');
					$this->setStatus(Document::STATUS_TOPAY);
					Yii::trace('Status TOPAY for '.$this->name, 'Document::deletePayment');
					$transaction->commit();
				} else if($payment->payment_method == Payment::CLEAR) {
					$transaction->rollback();
					Yii::$app->session->setFlash('danger', Yii::t('store', 'Restitution of refund/credit note is not handled yet.'));
					return;
				} else {
					if($account = Account::findOne($payment->account_id)) {
						if($payment->payment_method == Payment::CASH) {
							if($cash = Cash::findOne($payment->cash_id)) {
								$cash_date = $cash->created_at;
								$payment->delete();
								History::record($payment, 'DELETE', 'Cash payment deleted', true, null);
								if($do_account) $account->deleteWithCash();
								Yii::$app->session->setFlash('success', Yii::t('store', 'Cash payment deleted. {0} updated. You must review cash balance from {1}.',
											[$this->name, Yii::$app->formatter->asDate($cash_date)]));
								Yii::trace('Deleted cash for='.$this->name, 'Document::deletePayment');
							} else {
								Yii::$app->session->setFlash('danger', Yii::t('store', 'Cash payment not deleted because cash entry was not found.'));
								return;
							}
						} elseif ($payment->payment_method == Payment::METHOD_TRANSFER) {
							if($trans = BankTransaction::findOne($account->bank_transaction_id)) {
								Yii::trace('Found trans id '.$trans->id, 'Document::deletePayment');
								$trans->status = BankTransaction::STATUS_UPLOADED;
								$trans->save();
								History::record($trans, 'RESTORED', 'Bank restored', true, null);
								Yii::$app->session->setFlash('info', Yii::t('store', 'Bank transfer {0} restored.', $trans->name));
							}
							$payment->delete();
							History::record($payment, 'DELETE', 'Payment deleted with bank restored', true, null);
							if($do_account) $account->delete();
							Yii::$app->session->setFlash('success', Yii::t('store', 'Payment deleted. {0} updated.', [$this->name]));
							Yii::trace('Deleted payment for='.$this->name, 'Document::deletePayment');
						} else {
							$payment->delete();
							History::record($payment, 'DELETE', 'Payment deleted', true, null);
							if($do_account) $account->deleteWithCash();
							Yii::$app->session->setFlash('success', Yii::t('store', 'Payment deleted. {0} updated.', [$this->name]));
							Yii::trace('Deleted payment for='.$this->name, 'Document::deletePayment');
						}
						Yii::trace('Status TOPAY for '.$this->name, 'Document::deletePayment');
						$this->setStatus(Document::STATUS_TOPAY);
						$transaction->commit();
					} else {
						$transaction->rollback();
						Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not deleted because account entry was not found.'));
						return;
					}
				}
			}
		} else
			Yii::$app->session->setFlash('danger', Yii::t('store', 'Payment not found.'));
	}


	/**
	 * Change Paiments set the payment owner of all payments of this order to the client of this order
	 * Call to this function should be protected by a transaction.
	 */
	public function changePayments() {
		foreach($this->getPayments()->each() as $payment) {
			$payment->client_id = $this->client_id;
			if($account = $payment->getAccount()->one()) {
				$account->client_id = $this->client_id;
				$account->save();
			}
			$payment->save();
		}
	}

	/**
	 * Returns amount due.
	 *
	 * @return number Amount due.
	 */
	public function getBalance() {
		$balance = round($this->getTotal() - $this->getPrepaid(), 2);
		return abs($balance) > self::PAYMENT_LIMIT ? $balance : 0;
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
	 * 
	 */
	public static function updateSalePayments($sale) {
		
	}	
	/**
	 * Update payment status of document and triggers proper actions.
	 */
	protected function updatePaymentStatus() {
		if(!$this->isBusy()) {
			return $this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY;
		} // otherwise, we leave the status as it is
		return $this->status;
	}


	public function isBusy() {
		return $this->status == self::STATUS_TODO
		 	|| $this->status == self::STATUS_BUSY
		 	|| $this->status == self::STATUS_WARN;
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
		Yii::trace('today='.$today.', dayofweek='.$dayofweek.', requested='.$id);
		switch(intval($id)) {
			case self::DATE_TOMORROW: // next open day
				$nextday = ($dayofweek > 4) ? 3 : 1;
				$day = date('Y-m-d', strtotime('now + '.$nextday.' days'));
				$where = [$column => $day];
				break;
			case self::DATE_NEXT: // 2 days from now, but open day
				$nextday = ($dayofweek > 3) ? 4 : 2;
				$day = date('Y-m-d', strtotime('now + '.$nextday.' days'));
				$where = ['<', $column, $day];
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
			'{icon}', '<i class="glyphicon glyphicon-'.$icon.'"></i> ', str_replace(
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
	 * Generates belgian Structured Communication (XXX/XXXXX/XXYY) from supplied number.
	 * Pattern matched: [0-9]{3}\/[0-9]{5}\/[0-9]{4}, second, XXXXXXXXXX mod 97 = YY
	 *
	 * @return string Structurer Communication
	 */
	public static function commStruct($s=0) {
        $d=sprintf("%010s",preg_replace("/[^0-9]/", "", $s)); 
        $modulo=(bcmod($s,97)==0?97:bcmod($s,97)); 
        return sprintf("%s/%s/%s%02d",substr($d,0,3),substr($d,3,4),substr($d,7,3),$modulo); 
	}
	
	public function matches($str, $amount = null) {
		$c = $amount ? (($this->vat_bool ? $amount == $this->price_htva : $amount == $this->price_tvac) && $this->matches($str)) : true;
		return $c && preg_match('[0-9]{3}\/[0-9]{5}\/[0-9]{4}', $str);
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
	
	/**
	 * Returns email for notifications regarding this document
	 */
	public function getNotificationEmail() {
		return $this->email ? $this->email : $this->client->email;
	}
	

	public static function parseDateRange($field, $range, $query = null) {
		$q = $query ? $query : Document::find();
		if( ($sep = strpos($range, ' - ')) == 10) { // range in form of YYYY-MM-DD - YYYY-MM-DD.
			$date_from = substr($range, 0, 10).' 00:00:00';
			$date_to = substr($range, 13, 10).' 23:59:59';
			Yii::trace('From '.$date_from.' to '.$date_to, 'Document::parseDateRange');
	        $q->andWhere(['>=', $field, $date_from])
		      ->andWhere(['<=', $field, $date_to]);
		}
		return $q;
	}
	

	protected function addToDataProvider($dataProvider) {
		$dataProvider->sort->attributes['client_name'] = [
			'asc'  => ['client.nom' => SORT_ASC],
			'desc' => ['client.nom' => SORT_DESC],
		];
		$dataProvider->sort->attributes['created_at_range'] = [
			'asc'  => ['document.created_at' => SORT_ASC],
			'desc' => ['document.created_at' => SORT_DESC],
		];

		$dataProvider->sort->attributes['updated_at_range'] = [
			'asc'  => ['document.updated_at' => SORT_ASC],
			'desc' => ['document.updated_at' => SORT_DESC],
		];

		$dataProvider->sort->attributes['duedate_range'] = [
			'asc'  => ['document.due_date' => SORT_ASC],
			'desc' => ['document.due_date' => SORT_DESC],
		];
	}
	
	protected function addToQuery($query) {
		$query->andFilterWhere(['like', 'client.nom', $this->client_name]);
		$query = Document::parseDateRange('document.created_at', $this->created_at_range, $query);
		$query = Document::parseDateRange('document.updated_at', $this->updated_at_range, $query);
		$query = Document::parseDateRange('document.due_date',   $this->duedate_range, $query);
	}
	
	
	/**
	 * Explains status of document. If guessed status differs from current status, offers a link to change current status to guessed.
	 */
	protected function getLocalizedDoctype($lower = false) {
		if($this->document_type == self::TYPE_ORDER)
			$doc_type = $this->bom_bool ? Yii::t('store', self::TYPE_BOM) : Yii::t('store', $this->document_type);
		else
			$doc_type = Yii::t('store', $this->document_type);
		return $lower ? strtolower($doc_type) : $doc_type;
	}
	public function checkStatus() {
		$control_level = Parameter::getIntegerValue('application', 'control_level', 1);
		$newstatus = $this->guessStatus();
		$doctype = $this->getLocalizedDoctype(true);
		
		if($control_level > 0 && $this->status != $newstatus) {
			$this->blab(Html::a(Yii::t('store', 'Fix status to {0}', Yii::t('store', $newstatus)),
								[
									'fix-status',
									'id' => $this->id,
									'status' => $newstatus
								],[
									'title' => Yii::t('store', 'Fix status'),
					        		'data-confirm' => Yii::t('store', 'Are you sure you want to set status of this {0} to {1}?', [
															$doctype,
															Yii::t('store', $newstatus)
														]),
								]));
		}
		return $this->blabOut();
	}

	/**
	 * Tries to guess document status from document situation. May be wrong, especially when "annex" and related documents are involved.
	 */
	public function guessStatus() {
		$control_level = Parameter::getIntegerValue('application', 'control_level', 1);
		$newstatus = self::STATUS_CREATED;
		$doc_type = $this->getLocalizedDoctype(true);
		
		$created_at = $this->getCreatedBy()->one();
		$this->blab(Yii::t('store', '{2} created on {0} by {1}.', [$this->asDateTime($this->created_at), ($created_at ? $created_at->username : '?'), $doc_type]));
		
		if($this->status == self::STATUS_CREATED) {
			$this->blab(Yii::t('store', '{0} has no order line.', Yii::t('store', $doc_type)));
			return $this->blabOut();
		}
		
		if($this->status == self::STATUS_OPEN) {
			$start_order = [
				Document::TYPE_BID => 'Convert to Order',
				Document::TYPE_ORDER => 'Submit Work',
				Document::TYPE_BILL => 'Close',
				Document::TYPE_CREDIT => 'To Refund',
				Document::TYPE_TICKET => 'Submit Work',
				Document::TYPE_REFUND => 'To Refund',
			];
			$this->blab(Yii::t('store', '{0} is not filled. You have to press <q>{1}</q> to start fulfilling {0}.', [Yii::t('store', $doc_type), Yii::t('store', $start_order[$this->document_type])]));
			return $this->blabOut();
		}
		
		// WORK
		$work_completed = false;
		$work = $this->getWorks()->one();
		if($work) {
			$this->blab($work->checkStatus());
			$newstatus = $work->status;
			$work_completed = $work->status == Work::STATUS_DONE;
		} else {
			$newstatus = Work::STATUS_TODO;
			$this->blab(Yii::t('store', 'There is no work associated with this {0}.', $doc_type));
			if($this->status != Work::STATUS_TODO) {
				$work_completed = true;
				$newstatus = Work::STATUS_DONE;
			} else {
				$this->blab(Yii::t('store', '{0} needs to be marked as {1} to progress.', [$doc_type, self::STATUS_DONE]));
			}
		}
		
		$notify_completed = false;
		if($work_completed) {
			if($email = $this->getNotificationEmail()) {
				if($this->notified_at) {
					$this->blab(Yii::t('store', 'Client was notified on {0}.', $this->asDateTime($this->notified_at)));
					$notify_completed = true;
				} else {
					$this->blab(Yii::t('store', 'Client has not been notified yet.'));
					if($this->due_date > date("Y-m-d-H-i-s")) {
						$this->blab(Yii::t('store', 'Due date is {0}.', Yii::$app->formatter->asDate($this->due_date)));
						$days = Parameter::getIntegerValue('application', 'min_days', Order::DEFAULT_MINIMUM_DAYS);
						$date_notif = date('Y-m-d', strtotime($this->due_date.' - '.$days.' days'));
						$this->blab(Yii::t('store', 'Client will be notified on {0} at this address &lt;{1}&gt;.',
							[Yii::$app->formatter->asDate($date_notif), $email]));
					}
					$newstatus = self::STATUS_NOTIFY;
				}
			} else {
				$this->blab(Yii::t('store', 'Client will not be notified by email (no address).'));
				$notify_completed = true;
			}
		}

		if($notify_completed) {
			$newstatus = self::STATUS_TOPAY;
			// PAYMENT
			$total = $this->getAmount();
			$paid = $this->getPrepaid();
			$due = $total - $paid;
			$this->blab(Yii::t('store', 'Total for {1} is {0}.', [Yii::$app->formatter->asCurrency($total), $doc_type]));
			$this->blab(Yii::t('store', 'Payment received: {0}.', Yii::$app->formatter->asCurrency($paid)));
			if($this->isPaid()) {
				$this->blab(Yii::t('store', '{0} is paid.', $doc_type));
				$newstatus = self::STATUS_CLOSED;
			} else {
				$this->blab(Yii::t('store', 'Amount due: {0}.', Yii::$app->formatter->asCurrency($total	 - $paid)));
			}
		}
		
		if($this->document_type == self::TYPE_ORDER) {
			if($bill = $this->getBill()) {
				$this->blab(Yii::t('store', '{0} was billed on {1}.', [$doc_type, $this->asDateTime($bill->created_at)]).' '.
							Yii::t('store', 'Bill status is {0}.', Yii::t('store', $bill->status)));
				if(in_array($newstatus, [self::STATUS_DONE,self::STATUS_TOPAY]) && in_array($bill->status, [self::STATUS_TOPAY,self::STATUS_CLOSED])) {
					$newstatus = self::STATUS_CLOSED; // we can close this one since TOPAY status carried by bill
				}
			} else {
				$this->blab(Yii::t('store', '{0} has not been billed yet.', $doc_type));
			}
		}
		
		// Controls
		if($control_level > 1) {
			$this->blab('<hr/>');
			if($email = $this->getNotificationEmail()) {

				if($this->notified_at) {
					$this->blab(Yii::t('store', 'Client was notified on {0}.', $this->asDateTime($this->notified_at)));
					// FURTHER DOCS
					if($this->document_type == self::TYPE_ORDER) {
						if($bill = $this->getBill()) {
							$this->blab(Yii::t('store', 'Order billed on {0}.', $this->asDateTime($bill->created_at)));
						} else {
							$this->blab(Yii::t('store', 'Order not billed yet.'));
						}
					}
				} else {
					$this->blab(Yii::t('store', 'Client has not been notified yet.', Yii::$app->formatter->asDate($this->due_date)));
					$this->blab(Yii::t('store', 'Due date is {0}.', Yii::$app->formatter->asDate($this->due_date)));
					$days = Parameter::getIntegerValue('application', 'min_days', Order::DEFAULT_MINIMUM_DAYS);
					$date_notif = date('Y-m-d', strtotime($this->due_date.' - '.$days.' days'));
					$this->blab(Yii::t('store', 'Will be notified on this address &lt;{1}&gt; on {0}.', [Yii::$app->formatter->asDate($date_notif), $email]));
				}

			} else {
				$this->blab(Yii::t('store', 'Client will not be notified by email (no address).'));
			}
		}
		
		$this->blab(Yii::t('store', 'Current status is <q>{0}</q>.', Yii::t('store', $this->status)));

		return $newstatus;
	}

}