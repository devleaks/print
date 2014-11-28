<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "option".
 *
 */
class Option extends _Option
{
	/** Option "type" */
	const TYPE_BOOLEAN = 'BOOLEAN';
	/** Option "type" */
	const TYPE_RADIO = 'RADIO';
	/** Option "type" */
	const TYPE_DROPDOWN = 'DROPDOWN';

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
        ];
    }

	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @return array()
	 */
	public static function getOptionTypes() {
		return [
			self::TYPE_BOOLEAN => Yii::t('store', self::TYPE_BOOLEAN),
			self::TYPE_RADIO => Yii::t('store', self::TYPE_RADIO),
			self::TYPE_DROPDOWN => Yii::t('store', self::TYPE_DROPDOWN),
		];
	}
	
}
