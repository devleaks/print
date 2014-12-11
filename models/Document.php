<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Base class for all things common to bid, order, and bill
 *
 */
class Document extends _Document
{
	/** Order "type" */
	/** Devis */
	const TYPE_BID = 'BID';
	/** Commande */
	const TYPE_ORDER = 'ORDER';
	/** Facture */
	const TYPE_BILL = 'BILL';
	/** Note de crédit */
	const TYPE_CREDIT = 'CREDIT';
	/** Bon de livraison */
	const TYPE_BOM = 'BOM';
	/** Ticket de caisse */
	const TYPE_TICKET = 'TICKET';
	
	
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
	const STATUS_NOTE = 'NOTIFIED';
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
                        'value' => function() { return Yii::$app->user->id;},
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
		}
		return null;
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
			self::STATUS_NOTE => Yii::t('store', self::STATUS_NOTE),
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
			self::STATUS_NOTE => 'info',
			self::STATUS_OPEN => 'primary',
			self::STATUS_PAID => 'success',
			self::STATUS_TODO => 'primary',
			self::STATUS_WARN => 'warning',
		];
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

		/** Delete payments */
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
				//Yii::trace('Has rebate line '.$rebate_line->id);
				if(isset($rebate_line->extra_type) && ($rebate_line->extra_type != '')) {
					//Yii::trace('Has rebate type '.$rebate_line->extra_type);
					if(isset($rebate_line->extra_amount) && ($rebate_line->extra_amount > 0)) {
						//Yii::trace('Has rebate amount '.$rebate_line->extra_amount);
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

		//Yii::trace('total '.$this->price_htva);
		$this->price_htva = round($this->price_htva, 2);
		$this->price_tvac = round($this->price_tvac, 2);

		$this->save();
	}


	/** 
	 * Update status and reports to parent Work model
	 */
	public function setStatus($status) {
		$this->status = $status;
		$this->save();
		$this->updateStatus();
	}


	/**
	 * update status of document and triggers proper actions.
	 */
	public function updateStatus() {
	}

	/** 
	 * Update status and reports to parent Work model
	 */
	public function isPaid() {
		return (($this->price_tvac - $this->prepaid) < 0.01);
	}

	public function latestPayment() {
		$payments = $this->getPayments();
		$payments->orderBy('created_at desc');
		$last = $payments->one();
		return $last ? $last->amount : 0;
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
	public function convert() {
		if($this->document_type == self::TYPE_BILL) // no next operation after billing
			return null;

		$next_type = $this->document_type == Document::TYPE_BID ? Document::TYPE_ORDER :
						$this->document_type == Document::TYPE_ORDER ? Document::TYPE_BILL : null;

     	/** if a following document already exists, it returns it rather than create a new one. */
		if( $existing_next = $this->find()->andWhere(['parent_id' => $this->id])->andWhere(['document_type' => $next_type])->one() )
			return $existing_next;

		$new_type = ($this->document_type == self::TYPE_BID) ? self::TYPE_ORDER : self::TYPE_BILL;
		$copy = $this->deepCopy($new_type);
		$copy->parent_id = $this->id;
		
		if($this->document_type == self::TYPE_BID) { // if coming from bid to order, need to change reference to official order reference
			$copy->name = substr($copy->due_date,0,4).'-'.Sequence::nextval('order_number');
		}
		
		$copy->status = self::STATUS_OPEN;
		$copy->save();
		
		if($copy->document_type == self::TYPE_ORDER && Parameter::isTrue('application', 'auto_submit_work')) {
			Yii::trace('auto_submit_work for '.$copy->id);
			$work = $copy->createWork();
		} if($copy->document_type == self::TYPE_BILL && Parameter::isTrue('application', 'auto_send_bill')) {
			Yii::trace('auto_send_bill for '.$copy->id);
			$copy->send();
		}

		$this->status = self::STATUS_CLOSED;
		$this->save();	

		return $copy;
	}
	
	/**
	 *
	 * Note: $table introduced when joining several tables with due_date (ex. document and work)
	 */
	public static function getDateClause($id, $table = null) {
		$column = $table ? $table.'.due_date' : 'due_date';
		switch(intval($id)) {
			case 1: // tomorrow
			case 2: // after tomorrow
				$dayofweek = date('N'); // Mon=1, Sun=7
				$nextday = ($dayofweek > 4) ? $id + 2 : $id;
				$day = date('Y-m-d', strtotime('now + '.$nextday.' days'));
				$where = [$column => $day];
				break;
			case 7: // this week
				$day = date('Y-m-d', strtotime('now + 7 days'));
				$where = ['<', $column, $day];
				break;
			case -1: // late
				$day = date('Y-m-d', strtotime('today'));
				$where = ['<', $column, $day];
				break;
			case 0: // today
				$day = date('Y-m-d', strtotime('today'));
				$where = [$column => $day];
				break;
			default: // today
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
	 * Generates either action buttons or labels for Document depending on its status
	 *
	 * @return string HTML fragment
	 */
	public function getActions($baseclass = 'btn btn-xs btn-block', $show_work = false, $template = '{icon} {text}') {
		$ret = Html::a($this->getButton($template, 'eye-open', 'View'), ['/order/document/view', 'id' => $this->id], ['class' => $baseclass . ' btn-info']);
		return $ret;
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

	public static function commStruct($s=0) {
        $d=sprintf("%010s",preg_replace("/[^0-9]/", "", $s)); 
        $modulo=(bcmod($s,97)==0?97:bcmod($s,97)); 
        return sprintf("%s/%s/%s%02d",substr($d,0,3),substr($d,3,4),substr($d,7,3),$modulo); 
	}
}