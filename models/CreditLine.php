<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "extraction".
 *
 */
class CreditLine extends Model {
	
	/** */
	const SOURCE_CREDIT = 'CREDIT';
	/** */
	const SOURCE_REFUND = 'REFUND';
	/** */
	const SOURCE_ACCOUNT = 'ACCOUNT';
	
	public $ref;
	public $date;
	public $amount;
	public $note;
	public $source;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'note', 'note'], 'string'],
            [['amount', 'account', 'ref'], 'number'],
            [['date', 'note','amount', 'ref'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note' => Yii::t('store', 'Note'),
            'date' => Yii::t('store', 'Date'),
            'amount' => Yii::t('store', 'Amount'),
        ];
    }

	/**
	 * Does necessary thing to record credit use.
	 */
	public function useAmount($amount, $note) {
		if($this->source == self::SOURCE_CREDIT) {
			if ($doc = Credit::findOne($this->ref)) {
				Yii::trace('C='.$this->source.' '.$this->ref, 'CreditLine::useAmount');
				$doc->addPayment(- $amount, Payment::CLEAR, $note);
			}
		} elseif($this->source == self::SOURCE_REFUND) {
			if ($doc = Refund::findOne($this->ref)) {
				Yii::trace('R='.$this->source.' '.$this->ref, 'CreditLine::useAmount');
				$doc->addPayment(- $amount, Payment::CLEAR, $note);
			}
		} else { // must be SOURCE_ACCOUNT
			if($payment = Payment::findOne($this->ref)) {
				Yii::trace('ACCOUNT='.$this->source.' '.$this->ref, 'CreditLine::useAmount');
				$payment->note = $note; // .= $note?
				$payment->status = Payment::STATUS_PAID;
				$payment->save();
			}
		}
	}

}
