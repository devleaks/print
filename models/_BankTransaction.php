<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_transaction".
 *
 * @property integer $id
 * @property string $name
 * @property string $execution_date
 * @property string $value_date
 * @property double $amount
 * @property string $currency
 * @property string $source
 * @property string $note
 * @property string $account
 * @property string $status
 * @property string $created_at
 */
class _BankTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'execution_date', 'value_date', 'amount', 'currency', 'source', 'account', 'status', 'created_at'], 'required'],
            [['execution_date', 'value_date', 'created_at'], 'safe'],
            [['amount'], 'number'],
            [['name', 'currency', 'status'], 'string', 'max' => 20],
            [['source', 'account'], 'string', 'max' => 40],
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
            'name' => Yii::t('store', 'Name'),
            'execution_date' => Yii::t('store', 'Execution Date'),
            'value_date' => Yii::t('store', 'Value Date'),
            'amount' => Yii::t('store', 'Amount'),
            'currency' => Yii::t('store', 'Currency'),
            'source' => Yii::t('store', 'Source'),
            'note' => Yii::t('store', 'Note'),
            'account' => Yii::t('store', 'Account'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
        ];
    }
}
