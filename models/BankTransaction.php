<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bank_transaction".
 *
 * @property integer $id
 * @property string $name
 * @property string $execution_date
 * @property double $amount
 * @property string $currency
 * @property string $source
 * @property string $note
 * @property string $account
 * @property string $status
 * @property string $created_at
 */
class BankTransaction extends _BankTransaction
{
	const STATUS_UPLOADED = 'UPLOADED';
	const STATUS_PROCESSED = 'PROCESSED';
	const STATUS_REJECTED = 'PROCESSED';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'execution_date', 'amount', 'currency', 'source', 'account', 'status'], 'required'],
            [['execution_date', 'created_at'], 'safe'],
            [['amount'], 'number'],
            [['name', 'currency', 'status'], 'string', 'max' => 20],
            [['source', 'account'], 'string', 'max' => 40],
            [['note'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'BankTransaction'),
            'name' => Yii::t('store', 'Name'),
            'execution_date' => Yii::t('store', 'Execution Date'),
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
