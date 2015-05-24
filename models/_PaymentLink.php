<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_link".
 *
 * @property integer $id
 * @property integer $payment_id
 * @property integer $linked_id
 *
 * @property Payment $linked
 * @property Payment $payment
 */
class _PaymentLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'linked_id'], 'required'],
            [['payment_id', 'linked_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'payment_id' => Yii::t('store', 'Payment ID'),
            'linked_id' => Yii::t('store', 'Linked ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinked()
    {
        return $this->hasOne(Payment::className(), ['id' => 'linked_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
}
