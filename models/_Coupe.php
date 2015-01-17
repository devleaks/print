<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupe".
 *
 * @property integer $id
 * @property integer $document_line_id
 * @property double $work_length
 * @property double $quantity
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DocumentLine $documentLine
 */
class _Coupe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_line_id', 'work_length', 'quantity', 'created_at'], 'required'],
            [['document_line_id'], 'integer'],
            [['work_length', 'quantity'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'document_line_id' => Yii::t('store', 'Document Line ID'),
            'work_length' => Yii::t('store', 'Work Length'),
            'quantity' => Yii::t('store', 'Quantity'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLine()
    {
        return $this->hasOne(DocumentLine::className(), ['id' => 'document_line_id']);
    }
}
