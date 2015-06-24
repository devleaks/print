<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $cash_id
 * @property integer $bank_transaction_id
 * @property string $amount
 * @property string $payment_date
 * @property string $payment_method
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property Client $client
 * @property User $createdBy
 * @property User $updatedBy
 * @property BankTransaction $bankTransaction
 * @property Cash $cash
 * @property Payment[] $payments
 */
class _Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'amount'], 'required'],
            [['client_id', 'cash_id', 'bank_transaction_id', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['payment_date', 'created_at', 'updated_at'], 'safe'],
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
            'cash_id' => Yii::t('store', 'Cash ID'),
            'bank_transaction_id' => Yii::t('store', 'Bank Transaction ID'),
            'amount' => Yii::t('store', 'Amount'),
            'payment_date' => Yii::t('store', 'Payment Date'),
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankTransaction()
    {
        return $this->hasOne(BankTransaction::className(), ['id' => 'bank_transaction_id']);
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
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['account_id' => 'id']);
    }
}
