<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_sale".
 *
 * @property string $document_type
 * @property string $document_status
 * @property string $created_at
 * @property string $updated_at
 * @property string $due_date
 * @property string $price_htva
 * @property string $client_name
 * @property string $client_country


as select
   d.document_type as document_type,
   d.status as document_status,
   d.created_at as created_at,
   d.updated_at as updated_at,
   d.due_date as due_date,
   d.price_htva as price_htva,
   ifnull(concat(c.nom, ' ', c.prenom),c.autre_nom) as client_name,
   c.pays as client_country
from (document d join client c) where (d.client_id = c.id)



 */
class BiSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bi_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['due_date'], 'required'],
            [['price_htva'], 'number'],
            [['document_type', 'document_status'], 'string', 'max' => 20],
            [['client_name'], 'string', 'max' => 161],
            [['client_country'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_type' => Yii::t('store', 'Document Type'),
            'document_status' => Yii::t('store', 'Document Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'due_date' => Yii::t('store', 'Due Date'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'client_name' => Yii::t('store', 'Client Name'),
            'client_country' => Yii::t('store', 'Client Country'),
        ];
    }
}
