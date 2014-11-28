<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_line".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $position
 * @property double $quantity
 * @property double $unit_price
 * @property double $vat
 * @property string $note
 * @property double $work_width
 * @property double $work_height
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property double $price_htva
 * @property double $price_tvac
 * @property integer $item_id
 * @property double $extra_htva
 * @property double $extra_amount
 * @property string $extra_type
 * @property string $due_date
 *
 * @property Item $item
 * @property Order $order
 * @property OrderLineDetail[] $orderLineDetails
 * @property OrderLineOption[] $orderLineOptions
 * @property Picture[] $pictures
 * @property WorkLine[] $workLines
 */
class _OrderLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'quantity', 'item_id'], 'required'],
            [['order_id', 'position', 'item_id'], 'integer'],
            [['quantity', 'unit_price', 'vat', 'work_width', 'work_height', 'price_htva', 'price_tvac', 'extra_htva', 'extra_amount'], 'number'],
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['note'], 'string', 'max' => 160],
            [['status', 'extra_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'order_id' => Yii::t('store', 'Order ID'),
            'position' => Yii::t('store', 'Position'),
            'quantity' => Yii::t('store', 'Quantity'),
            'unit_price' => Yii::t('store', 'Unit Price'),
            'vat' => Yii::t('store', 'Vat'),
            'note' => Yii::t('store', 'Note'),
            'work_width' => Yii::t('store', 'Work Width'),
            'work_height' => Yii::t('store', 'Work Height'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'price_tvac' => Yii::t('store', 'Price Tvac'),
            'item_id' => Yii::t('store', 'Item ID'),
            'extra_htva' => Yii::t('store', 'Extra Htva'),
            'extra_amount' => Yii::t('store', 'Extra Amount'),
            'extra_type' => Yii::t('store', 'Extra Type'),
            'due_date' => Yii::t('store', 'Due Date'),
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
    public function getOrder()
    {
        return $this->hasOne(Document::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderLineDetails()
    {
        return $this->hasMany(OrderLineDetail::className(), ['order_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderLineOptions()
    {
        return $this->hasMany(OrderLineOption::className(), ['order_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPictures()
    {
        return $this->hasMany(Picture::className(), ['order_line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['order_line_id' => 'id']);
    }
}
