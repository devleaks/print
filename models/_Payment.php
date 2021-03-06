<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $sale
 * @property integer $cash_id
 * @property integer $account_id
 * @property string $amount
 * @property string $payment_method
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Account $account
 * @property User $createdBy
 * @property User $updatedBy
 * @property Client $client
 * @property Cash $cash
 * @property PaymentLink[] $paymentLinks
 * @property PaymentLink[] $paymentLinks0
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
            [['client_id', 'sale', 'cash_id', 'account_id', 'created_by', 'updated_by'], 'integer'],
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
            'sale' => Yii::t('store', 'Sale'),
            'cash_id' => Yii::t('store', 'Cash ID'),
            'account_id' => Yii::t('store', 'Account ID'),
            'amount' => Yii::t('store', 'Amount'),
            'payment_method' => Yii::t('store', 'Payment Method'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'updated_by' => Yii::t('store', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
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
    public function getCash()
    {
        return $this->hasOne(Cash::className(), ['id' => 'cash_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentLinks()
    {
        return $this->hasMany(PaymentLink::className(), ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentLinks0()
    {
        return $this->hasMany(PaymentLink::className(), ['linked_id' => 'id']);
    }
}
