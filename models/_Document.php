<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property string $document_type
 * @property integer $sale
 * @property string $reference
 * @property string $reference_client
 * @property string $name
 * @property integer $parent_id
 * @property integer $client_id
 * @property string $due_date
 * @property double $price_htva
 * @property double $price_tvac
 * @property double $vat
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
 *
 * @property Account[] $accounts
 * @property User $updatedBy
 * @property _Document $parent
 * @property _Document[] $documents
 * @property Client $client
 * @property User $createdBy
 * @property DocumentLine[] $documentLines
 * @property Extraction[] $extractions
 * @property Work[] $works
 */
class _Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale', 'name', 'client_id', 'due_date'], 'required'],
            [['sale', 'parent_id', 'client_id', 'vat_bool', 'bom_bool', 'created_by', 'updated_by', 'priority'], 'integer'],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['price_htva', 'price_tvac', 'vat'], 'number'],
            [['document_type', 'name', 'lang', 'status'], 'string', 'max' => 20],
            [['reference', 'reference_client'], 'string', 'max' => 40],
            [['note'], 'string', 'max' => 160]
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
            'sale' => Yii::t('store', 'Sale'),
            'reference' => Yii::t('store', 'Reference'),
            'reference_client' => Yii::t('store', 'Reference Client'),
            'name' => Yii::t('store', 'Name'),
            'parent_id' => Yii::t('store', 'Parent'),
            'client_id' => Yii::t('store', 'Client'),
            'due_date' => Yii::t('store', 'Due Date'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'price_tvac' => Yii::t('store', 'Price Tvac'),
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(_Document::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(_Document::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLines()
    {
        return $this->hasMany(DocumentLine::className(), ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtractions()
    {
        return $this->hasMany(Extraction::className(), ['document_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['document_id' => 'id']);
    }
}
