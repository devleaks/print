<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment".
 *
 */
class Payment extends _Payment
{
	public $payment_date;
	/** Payment Methods */
	const CASH = 'CASH';
	
	const USE_CREDIT = 'CREDIT';
	const CLEAR = 'CLEAR';
	
	const METHOD_TRANSFER = 'TRANSFER';
	const METHOD_OLDSYSTEM = 'OLDSYSTEM';

	/** Document status */
	const STATUS_PAID = 'PAID';	
	/** */
	const STATUS_OPEN = 'OPEN';
	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
	 * returns associative array of payment methods
	 *
	 * @return array()
	 */
	public static function getPaymentMethods($exclude_cash = false) {
		return $exclude_cash ?
			ArrayHelper::map(Parameter::find()->where(['domain'=>'payment'])->andWhere(['not', ['name' => Payment::CASH]])->orderBy('value_int')->asArray()->all(), 'name', 'value_text')
			:
			ArrayHelper::map(Parameter::find()->where(['domain'=>'payment'])->orderBy('value_int')->asArray()->all(), 'name', 'value_text')
			;
	}
	
	public function getPaymentMethod() {
		$method = $this->account ? $this->account->payment_method : $this->payment_method;
		if($method == Payment::CLEAR)
			return Yii::t('store', 'Credit Clearance');
		elseif($method == Payment::USE_CREDIT)
			return Yii::t('store', 'Credit Used');
		$p = Parameter::findOne(['domain'=>'payment', 'name' => $method]);
		return $p ? $p->value_text : $this->payment_method.'?';
	}
	
	public function getStatusLabel() {
		return '<span class="label label-'.($this->status == 'PAID' ? 'success' : 'warning').'">'.Yii::t('store', $this->status).'</span>';
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return Document::find()->where(['sale' => $this->sale])->orderBy('created_at desc')->limit(1);
    }

	/**
	 * Whether this payment was part of a global payment or not.
	 */
	public function partOfMultiplePayment() {
		// not 100% correct: What is all payments are form the same sale... should check how many distinct sales there are. Later.
		if($account = $this->getAccount()->one()) {
			return $account->getPayments()->count() > 1;
		}
		return false;
	}
	
	/**
	 * Returns money operation's date
	 */
	public function getPaymentDate() {
		// return $this->created_at;
		return $this->account ? $this->account->payment_date : $this->created_at;
	}

}