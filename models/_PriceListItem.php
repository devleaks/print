<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price_list_item".
 *
 * @property integer $id
 * @property integer $price_list_id
 * @property integer $item_id
 * @property integer $position
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 * @property PriceList $priceList
 */
class _PriceListItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_list_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_list_id', 'item_id', 'position'], 'required'],
            [['price_list_id', 'item_id', 'position'], 'integer'],
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
            'price_list_id' => Yii::t('store', 'Price List'),
            'item_id' => Yii::t('store', 'Item'),
            'position' => Yii::t('store', 'Position'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
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
    public function getPriceList()
    {
        return $this->hasOne(PriceList::className(), ['id' => 'price_list_id']);
    }
}
