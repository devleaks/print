<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 *	Gii Extension class
 */
class Account extends _Account
{
	/** Bulk action ID */
	const ACTION_ADD_PAYMENT = 'PAY';
	/** Bulk action ID */
	const ACTION_SEND_REMINDER = 'SEND';
	/** Bulk action ID */
	const ACTION_TRANSFER = 'TRANSFER';
	/** */
	const TYPE_DEBIT  = 'ADEBIT';
	/** */
	const TYPE_CREDIT = 'ACREDIT';
	/** */
	const TYPE_BALANCED = 'BALANCED';
	
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
        ];
    }

	protected function getStatusColor() {
		$color = [
			self::TYPE_DEBIT => 'danger',
			self::TYPE_CREDIT => 'success',
			self::TYPE_BALANCED => 'info',
		];
		return $color[$this->status];
	}
	
	public function getStatuses() {
		return [
			self::TYPE_DEBIT  => Yii::t('store', self::TYPE_DEBIT),
			self::TYPE_CREDIT => Yii::t('store', self::TYPE_CREDIT),
		];
	}

	public function getStatusLabel() {
		return '<span class="label label-'.$this->getStatusColor().'">'.Yii::t('store', $this->status).'</span>';
	}
	
	public function getBalance($client_id, $last_date = null) {
		$q = Account::find()->andWhere(['client_id' => $client_id]);
		if($last_date)
			$q->andWhere(['<=','created_at',$last_date]);
		return round($q->sum('amount'), 2);
	}

	public function getUnpaid($client_id, $last_date = null) {
		$q = Account::find()
			->andWhere(['client_id' => $client_id])
			->andWhere(['status' => Account::TYPE_DEBIT]);
		if($last_date)
			$q->andWhere(['<=','created_at',$last_date]);
		return round($q->sum('amount'), 2);
	}

}