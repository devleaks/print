<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "extraction".
 *
 */
class Payment extends _Payment
{
	/** Payment Methods */
	const CASH = 'CASH';
	
	const USE_CREDIT = 'CREDIT';
	const CLEAR = 'CLEAR';

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
	public static function getPaymentMethods() {
		return ArrayHelper::map(Parameter::find()->where(['domain'=>'payment'])->orderBy('value_int')->asArray()->all(), 'name', 'value_text');
	}
	
	public function getPaymentMethod() {
		if($this->payment_method == Payment::CLEAR)
			return Yii::t('store', 'Bill Debit');
		elseif($this->payment_method == Payment::USE_CREDIT)
			return Yii::t('store', 'Credit Used');
		$p = Parameter::findOne(['domain'=>'payment', 'name' => $this->payment_method]);
		return $p ? $p->value_text : null;
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
}
