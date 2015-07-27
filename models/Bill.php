<?php

namespace app\models;

use Yii;

class Bill extends Document {
	const TYPE = 'BILL';
	
	/** Bulk action ID */
	const ACTION_PAYMENT_RECEIVED = 'PAY';
	/** Bulk action ID */
	const ACTION_SEND_REMINDER = 'SEND';
	/** Bulk action ID */
	const ACTION_CLIENT_ACCOUNT = 'ACCOUNT';
	/** Bulk action ID */
	const ACTION_EXTRACT = 'EXTRACT';

	const BILL_NUMBER_LENGTH = 4;
	
    /**
     * @inheritdoc
	 */
	public static function defaultScope($query)
    {
		Yii::trace(self::TYPE_BILL, 'Bill::defaultScope');
        $query->andWhere(['document_type' => self::TYPE_BILL]);
    }

	public function getRelatedReference() {
		if($this->bom_bool) {
			$str = '';
			foreach(Order::find()->andWhere(['bom_bool' => true, 'parent_id' => $this->id])->each() as $order) {
				$str .= $order->name.', ';				
			}
			return trim($str, ', ');
		} else {
			return $this->parent ? $this->parent->name : '';
		}
	}
	
	
	/**
	 *
	 */
	public function send() {
		$this->status = ($this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY);
		$this->save();
	}
	
	
	/**
	 * @inheritdoc
	 */
	protected function updatePaymentStatus() {
		return $this->isPaid() ? self::STATUS_CLOSED : self::STATUS_TOPAY;
	}

	public function getPrepaid($today = false) {
		$sales = [];
		$sales[] = $this->sale;
		if($this->bom_bool) {
			foreach(Order::find()->andWhere(['bom_bool' => true, 'parent_id' => $this->id])->each() as $order) {
				$sales[] = $order->sale;				
			}
		} else {
			if($order = Order::findOne(['id' => $this->parent_id])) {
				$sales[] = $order->sale;				
			}
		}

		if($today) {
			$date_from = date('Y-m-d 00:00:00', strtotime('today'));
			$date_to = str_replace($date_from, '00:00:00', '23:59:59');
			$ret = Payment::find()->andWhere(['sale' => $sales])
							->andWhere(['>=','created_at',$date_from])
							->andWhere(['<=','created_at',$date_to])
							->sum('amount');	
		} else
			$ret = Payment::find()->where(['sale' => $sales])->sum('amount');

		return $ret ? $ret : 0;
	}

	/**
	 *
	 */
	public function createFromBoms($boms) {
		if(!count($boms) > 0)
			return null;
		$boms = Order::find()->andWhere(['bom_bool' => true, 'id' => $boms])->orderBy('created_at');
		if($boms->exists()) {
			Yii::trace('bill='.$this->id, 'Bill::createFromBoms');
			if($boms->created_at < '2015-06-24') {
				$boms->updatePrice();
			}
			$model = null;
			$vat_bool = null;
			foreach($boms->each() as $bom) {
				$line = 1;
				if(! $model) {
					$model = new Bill();
					$model->document_type = self::TYPE_BILL;
					$model->bom_bool = 1; // we know the bill is made of BOM
					$model->id = null;
					$model->client_id = $bom->client_id;
					$model->vat_bool = $bom->vat_bool; // we assume all BOMs have same vat_bool.
					$model->due_date = $bom->due_date;
					// Note: BOM gets temporary number, bill gets next number available.
					$model->name = substr($bom->due_date,0,4).'-'.Sequence::nextval('bill_number');
					//$model->note = $bom->name;
					$model->status = self::STATUS_OPEN;
					$model->sale = Sequence::nextval('sale');
					$model->reference = $model->commStruct(date('y')*10000000 + $model->sale);
					$model->save();
					$vat_bool = $bom->vat_bool;
				} else {
					if($vat_bool != $bom->vat_bool) {
						Yii::trace('bom='.$bom->id.' has different VAT convention', 'Bill::createFromBoms');
						return null;
					}
				}
				// add order lines from bom to bill
				foreach($bom->getDocumentLines()->each() as $ol) {
					$bl = $ol->deepCopy($model->id);
					$sep = $bl->note ? '/' : '';
					$bl->note = self::append($bl->note,
											($ol->item->reference === Item::TYPE_REBATE)
										? $sep.Yii::t('store', $bl->extra_htva > 0 ? 'Supplement' : 'Rebate').' '.Yii::t('store', 'for').' '.$bom->name.'.'
										: $sep.$bom->name/*.':'.$line*/.'.'
								, ' ', 160);
					$bl->save();
					$line++;
				}
				
				$last_date = $bom->due_date;
				//$bom->setStatus(Document::STATUS_CLOSED);
				Yii::trace('bom='.$bom->id, 'Bill::createFromBoms');
				$bom->parent_id = $model->id; // inverse relation, should be children...
				$bom->bill_id = $model->id;
				$bom->save();
			} // foreach BOM
			$model->due_date = $last_date;
			$model->updatePrice(false);	// do NOT update REBATE lines
			$model->status = $model->updatePaymentStatus();
			if($model->status == self::STATUS_TOPAY && Parameter::isTrue('application', 'auto_send_bill')) { // auto send bill if necessary
				$model->send();
			}
			$model->save();
			return $model;
		}	
		return null;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
		if($this->bom_bool) { // this bill comes from a list of BOM
			$bom_sales = [];
			$bom_sales[] = $this->sale; // payments made on bill
			foreach(Order::find()->andWhere(['bom_bool' => true, 'parent_id' => $this->id])->each() as $bom) {
				Yii::trace('adding '.$bom->sale);
				$bom_sales[] = $bom->sale; // build array os sales id from all boms in this bill
			}
			return Payment::find()->andWhere(['sale' => $bom_sales]); // find payments made for this list of sales
		} else
        	return $this->hasMany(Payment::className(), ['sale' => 'sale']);
    }


    /**
     * @inheritdoc
	 */
	public function canModify() {
		/** once a bill has been emitted, it cannot be changed. */
		return ($this->status == Document::STATUS_OPEN || $this->status == Document::STATUS_CREATED);
	}

    /**
     * @inheritdoc
	 */
	public function getActions($show_work = false) {
		$actions = [];
		switch($this->status) {
			case $this::STATUS_OPEN:
				$actions[] = '{send}';
				break;
			case $this::STATUS_CLOSED:
				$actions[] = '{label:closed}';
				break;
		}
		return implode(' ', $actions) . ' ' . parent::getActions();
	}

}