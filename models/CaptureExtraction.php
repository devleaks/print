<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "extraction".
 *
 */
class CaptureExtraction extends Model
{
	/** Option "type" */
	const TYPE_BILL   = 'BILL';
	/** Option "type" */
	const TYPE_CREDIT = 'CREDIT';


	/** Option "method" */
	const METHOD_DATE = 'DATE';
	/** Option "type" */
	const METHOD_REFN = 'REFN';


	public $extraction_type;
	public $extraction_method;
	public $document_from ;
	public $document_to;
	public $date_from;
	public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_from', 'date_to'], 'safe'],
            [['document_from', 'document_to'], 'integer'],
            [['extraction_type', 'extraction_method'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'extraction_type' => Yii::t('store', 'Document Type'),
            'date_from' => Yii::t('store', 'Date From'),
            'date_to' => Yii::t('store', 'Date To'),
            'document_from' => Yii::t('store', 'Document From'),
            'document_to' => Yii::t('store', 'Document To'),
            'extraction_method' => Yii::t('store', 'Extraction Method'),
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
