<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $client_id
 * @property double $amount
 * @property string $payment_method
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $sale
 *
 * @property User $updatedBy
 * @property Client $client
 * @property User $createdBy
 */
class _Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'sale'], 'required'],
            [['client_id', 'created_by', 'updated_by', 'sale'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment_method', 'status'], 'string', 'max' => 20],
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
            'client_id' => Yii::t('store', 'Client ID'),
            'amount' => Yii::t('store', 'Amount'),
            'payment_method' => Yii::t('store', 'Payment Method'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'updated_by' => Yii::t('store', 'Updated By'),
            'sale' => Yii::t('store', 'Sale'),
        ];
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
}
