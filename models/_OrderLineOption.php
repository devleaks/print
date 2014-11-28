<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_line_option".
 *
 * @property integer $id
 * @property integer $order_line_id
 * @property integer $option_id
 * @property double $option_price
 * @property string $created_at
 * @property string $updated_at
 * @property integer $item_id
 *
 * @property Item $item
 * @property OrderLine $orderLine
 * @property Option $option
 */
class _OrderLineOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_line_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_line_id', 'item_id'], 'required'],
            [['order_line_id', 'option_id', 'item_id'], 'integer'],
            [['option_price'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'order_line_id' => Yii::t('store', 'Order Line ID'),
            'option_id' => Yii::t('store', 'Option ID'),
            'option_price' => Yii::t('store', 'Option Price'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'item_id' => Yii::t('store', 'Item ID'),
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
    public function getOrderLine()
    {
        return $this->hasOne(OrderLine::className(), ['id' => 'order_line_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }
}
