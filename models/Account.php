<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 *	Gii Extension class
 */
class Account extends _Account
{
	/** */
	const TYPE_DEBIT  = 'ADEBIT';
	/** */
	const TYPE_CREDIT = 'ACREDIT';
	
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

	public function getStatuses() {
		return [
			self::TYPE_DEBIT  => Yii::t('store', self::TYPE_DEBIT),
			self::TYPE_CREDIT => Yii::t('store', self::TYPE_CREDIT),
		];
	}

	public function getStatusLabel() {
		return '<span class="label label-'.($this->status == self::TYPE_DEBIT ? 'success' : 'danger').'">'.Yii::t('store', $this->status).'</span>';
	}
}