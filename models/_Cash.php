<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash".
 *
 * @property integer $id
 * @property integer $document_id
 * @property integer $sale
 * @property double $amount
 * @property string $payment_date
 * @property string $note
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Document $document
 * @property User $createdBy
 * @property User $updatedBy
 */
class _Cash extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cash';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'sale', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['payment_date', 'created_at', 'updated_at'], 'safe'],
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
            'document_id' => Yii::t('store', 'Document ID'),
            'sale' => Yii::t('store', 'Sale'),
            'amount' => Yii::t('store', 'Amount'),
            'payment_date' => Yii::t('store', 'Payment Date'),
            'note' => Yii::t('store', 'Note'),
            'created_at' => Yii::t('store', 'Created At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'updated_by' => Yii::t('store', 'Updated By'),
        ];
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
