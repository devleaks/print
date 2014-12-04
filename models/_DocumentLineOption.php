<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_line_option".
 *
 * @property integer $id
 * @property integer $option_id
 * @property integer $item_id
 * @property double $price
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property integer $document_line_id
 *
 * @property Item $item
 * @property DocumentLine $documentLine
 * @property Option $option
 */
class _DocumentLineOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_line_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'item_id', 'document_line_id'], 'integer'],
            [['item_id', 'document_line_id'], 'required'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'option_id' => Yii::t('store', 'Option ID'),
            'item_id' => Yii::t('store', 'Item ID'),
            'price' => Yii::t('store', 'Price'),
            'note' => Yii::t('store', 'Note'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'document_line_id' => Yii::t('store', 'Document Line ID'),
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
    public function getDocumentLine()
    {
        return $this->hasOne(DocumentLine::className(), ['id' => 'document_line_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }
}
