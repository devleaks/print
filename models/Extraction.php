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
	const TYPE_BILL   = 'BILL';
	/** Option "type" */
	const TYPE_CREDIT = 'CREDIT';


	/** Option "type" */
	const METHOD_DATE = 'DATE';
	/** Option "type" */
	const METHOD_REFN = 'REFN';

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
			self::TYPE_BILL => Yii::t('store', self::TYPE_BILL),
			self::TYPE_CREDIT => Yii::t('store', self::TYPE_CREDIT),
		];
	}
	
	/**
	 * returns associative array of status, status localized display for all possible status values
	 *
	 * @return array()
	 */
	public static function getExtractionMethods() {
		return [
			self::METHOD_DATE => Yii::t('store', self::METHOD_DATE),
			self::METHOD_REFN => Yii::t('store', self::METHOD_REFN),
		];
	}
	
}
