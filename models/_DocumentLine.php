<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_line".
 *
 * @property integer $id
 * @property integer $document_id
 * @property integer $item_id
 * @property integer $position
 * @property double $work_width
 * @property double $work_height
 * @property double $unit_price
 * @property double $quantity
 * @property string $extra_type
 * @property double $extra_amount
 * @property double $extra_htva
 * @property double $price_htva
 * @property double $price_tvac
 * @property double $vat
 * @property string $due_date
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $priority
 * @property string $location
 *
 * @property Item $item
 * @property Document $document
 * @property DocumentLineDetail[] $documentLineDetails
 * @property DocumentLineOption[] $documentLineOptions
 * @property Picture[] $pictures
 * @property WorkLine[] $workLines
 */
class _DocumentLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'item_id', 'quantity'], 'required'],
            [['document_id', 'item_id', 'position', 'priority'], 'integer'],
            [['work_width', 'work_height', 'unit_price', 'quantity', 'extra_amount', 'extra_htva', 'price_htva', 'price_tvac', 'vat'], 'number'],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['extra_type', 'status'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 160],
            [['location'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'document_id' => Yii::t('store', 'Document'),
            'item_id' => Yii::t('store', 'Item'),
            'position' => Yii::t('store', 'Position'),
            'work_width' => Yii::t('store', 'Work Width'),
            'work_height' => Yii::t('store', 'Work Height'),
            'unit_price' => Yii::t('store', 'Unit Price'),
            'quantity' => Yii::t('store', 'Quantity'),
            'extra_type' => Yii::t('store', 'Extra Type'),
            'extra_amount' => Yii::t('store', 'Extra Amount'),
            'extra_htva' => Yii::t('store', 'Extra Htva'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'price_tvac' => Yii::t('store', 'Price TVAC'),
            'vat' => Yii::t('store', 'Vat'),
            'due_date' => Yii::t('store', 'Due Date'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'priority' => Yii::t('store', 'Priority'),
            'location' => Yii::t('store', 'Location'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineDetails()
    {
        return $this->hasMany(DocumentLineDetail::className(), ['document_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLineOptions()
    {
        return $this->hasMany(DocumentLineOption::className(), ['document_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPictures()
    {
        return $this->hasMany(Picture::className(), ['document_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['document_line_id' => 'id']);
    }
}
