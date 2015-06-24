<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_archive".
 *
 * @property integer $id
 * @property string $document_type
 * @property string $name
 * @property integer $sale
 * @property string $reference
 * @property string $reference_client
 * @property integer $parent_id
 * @property integer $client_id
 * @property string $due_date
 * @property string $price_htva
 * @property string $price_tvac
 * @property string $vat
 * @property integer $vat_bool
 * @property integer $bom_bool
 * @property string $note
 * @property string $lang
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $priority
 * @property string $legal
 * @property string $email
 * @property integer $credit_bool
 */
class _DocumentArchive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_archive';
    }

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
            [['email'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'document_type' => Yii::t('store', 'Document Type'),
            'name' => Yii::t('store', 'Name'),
            'sale' => Yii::t('store', 'Sale'),
            'reference' => Yii::t('store', 'Reference'),
            'reference_client' => Yii::t('store', 'Reference Client'),
            'parent_id' => Yii::t('store', 'Parent ID'),
            'client_id' => Yii::t('store', 'Client ID'),
            'due_date' => Yii::t('store', 'Due Date'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'price_tvac' => Yii::t('store', 'Price TVAC'),
            'vat' => Yii::t('store', 'Vat'),
            'vat_bool' => Yii::t('store', 'Vat Bool'),
            'bom_bool' => Yii::t('store', 'Bom Bool'),
            'note' => Yii::t('store', 'Note'),
            'lang' => Yii::t('store', 'Lang'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'updated_by' => Yii::t('store', 'Updated By'),
            'priority' => Yii::t('store', 'Priority'),
            'legal' => Yii::t('store', 'Legal'),
            'email' => Yii::t('store', 'Email'),
            'credit_bool' => Yii::t('store', 'Credit Bool'),
        ];
    }
}
