<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to cature email address.
 */
class CaptureArchive extends _DocumentArchive
{
	public $price_htva_virgule;
	public $price_tvac_virgule;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sale', 'parent_id', 'client_id', 'vat_bool', 'bom_bool', 'created_by', 'updated_by', 'priority', 'credit_bool'], 'integer'],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['price_htva', 'price_tvac', 'vat'], 'number'],
            [['document_type', 'name', 'lang', 'status'], 'string', 'max' => 20],
            [['reference', 'reference_client'], 'string', 'max' => 40],
            [['note', 'legal'], 'string', 'max' => 160],
            [['email'], 'string', 'max' => 80],
			[['price_htva_virgule', 'price_tvac_virgule'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9]/'],
			[['price_htva_virgule', 'price_tvac_virgule'], 'safe'],
            [['name', 'due_date', 'price_tvac_virgule'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'document_type' => Yii::t('store', 'Archive Type'),
	        'due_date' => Yii::t('store', 'Date'),
	        'price_htva' => Yii::t('store', 'Amount HTVA'),
	        'price_tvac' => Yii::t('store', 'Amount TVAC'),
	        'price_htva_virgule' => Yii::t('store', 'Amount HTVA'),
	        'price_tvac_virgule' => Yii::t('store', 'Amount TVAC'),
        ]);
    }
}
