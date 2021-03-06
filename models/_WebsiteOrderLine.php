<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "website_order_line".
 *
 * @property integer $id
 * @property integer $website_order_id
 * @property string $filename
 * @property string $finish
 * @property string $profile
 * @property integer $quantity
 * @property string $format
 * @property string $comment
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $height
 * @property integer $width
 *
 * @property WebsiteOrder $websiteOrder
 */
class _WebsiteOrderLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'website_order_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['website_order_id'], 'required'],
            [['website_order_id', 'quantity', 'height', 'width'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 80],
            [['finish', 'profile', 'format', 'status'], 'string', 'max' => 20],
            [['comment'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'website_order_id' => Yii::t('store', 'Website Order ID'),
            'filename' => Yii::t('store', 'Filename'),
            'finish' => Yii::t('store', 'Finish'),
            'profile' => Yii::t('store', 'Profile'),
            'quantity' => Yii::t('store', 'Quantity'),
            'format' => Yii::t('store', 'Format'),
            'comment' => Yii::t('store', 'Comment'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'height' => Yii::t('store', 'Height'),
            'width' => Yii::t('store', 'Width'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteOrder()
    {
        return $this->hasOne(WebsiteOrder::className(), ['id' => 'website_order_id']);
    }
}
