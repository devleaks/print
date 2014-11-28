<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_line_detail".
 *
 * @property integer $id
 * @property integer $order_line_id
 * @property integer $frame_id
 * @property integer $support_id
 * @property integer $tirage_id
 * @property integer $collage_id
 * @property integer $protection_id
 * @property double $price_chroma
 * @property double $price_frame
 * @property double $price_montage
 * @property double $price_protection
 * @property integer $chroma_id
 * @property integer $corner_bool
 * @property double $price_corner
 * @property integer $montage_bool
 * @property double $price_support
 * @property double $price_tirage
 * @property double $price_collage
 * @property integer $finish_id
 * @property integer $renfort_bool
 * @property double $price_renfort
 * @property string $note
 *
 * @property Item $protection
 * @property Item $finish
 * @property Item $support
 * @property Item $tirage
 * @property Item $collage
 * @property Item $chroma
 * @property Item $frame
 * @property OrderLine $orderLine
 */
class _OrderLineDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_line_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_line_id'], 'required'],
            [['order_line_id', 'frame_id', 'support_id', 'tirage_id', 'collage_id', 'protection_id', 'chroma_id', 'corner_bool', 'montage_bool', 'finish_id', 'renfort_bool'], 'integer'],
            [['price_chroma', 'price_frame', 'price_montage', 'price_protection', 'price_corner', 'price_support', 'price_tirage', 'price_collage', 'price_renfort'], 'number'],
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
            'order_line_id' => Yii::t('store', 'Order Line'),
            'frame_id' => Yii::t('store', 'Frame'),
            'support_id' => Yii::t('store', 'Châssis Canvas'),
            'tirage_id' => Yii::t('store', 'Tirage'),
            'collage_id' => Yii::t('store', 'Collage'),
            'protection_id' => Yii::t('store', 'Protection'),
            'price_chroma' => Yii::t('store', 'Price Chroma'),
            'price_frame' => Yii::t('store', 'Price Frame'),
            'price_montage' => Yii::t('store', 'Price Montage'),
            'price_protection' => Yii::t('store', 'Price Protection'),
            'chroma_id' => Yii::t('store', 'Chroma'),
            'corner_bool' => Yii::t('store', 'Corner Bool'),
            'price_corner' => Yii::t('store', 'Price Corner'),
            'montage_bool' => Yii::t('store', 'Montage Bool'),
            'price_support' => Yii::t('store', 'Price Support'),
            'price_tirage' => Yii::t('store', 'Price Tirage'),
            'price_collage' => Yii::t('store', 'Price Collage'),
            'finish_id' => Yii::t('store', 'Finish'),
            'renfort_bool' => Yii::t('store', 'Renfort Bool'),
            'price_renfort' => Yii::t('store', 'Price Renfort'),
            'note' => Yii::t('store', 'Note'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProtection()
    {
        return $this->hasOne(Item::className(), ['id' => 'protection_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinish()
    {
        return $this->hasOne(Item::className(), ['id' => 'finish_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupport()
    {
        return $this->hasOne(Item::className(), ['id' => 'support_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTirage()
    {
        return $this->hasOne(Item::className(), ['id' => 'tirage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollage()
    {
        return $this->hasOne(Item::className(), ['id' => 'collage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChroma()
    {
        return $this->hasOne(Item::className(), ['id' => 'chroma_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrame()
    {
        return $this->hasOne(Item::className(), ['id' => 'frame_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderLine()
    {
        return $this->hasOne(OrderLine::className(), ['id' => 'order_line_id']);
    }
}
