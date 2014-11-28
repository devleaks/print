<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "extraction".
 *
 */
class Extraction extends _Extraction
{
	/** Option "type" */
	const TYPE_DATE = 'DATE';
	/** Option "type" */
	const TYPE_REFN = 'REFN';
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
	public static function getExtractionTypes() {
		return [
			self::TYPE_DATE => Yii::t('store', self::TYPE_DATE),
			self::TYPE_REFN => Yii::t('store', self::TYPE_REFN),
		];
	}
	
}
