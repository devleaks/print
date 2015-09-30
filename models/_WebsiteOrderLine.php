<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "website_order_line".
 *
 * @property integer $id
 * @property integer $website_order_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $filename
 * @property string $finish
 * @property integer $profile_bool
 * @property integer $quantitiy
 * @property string $format
 * @property string $comment
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
            [['website_order_id', 'profile_bool', 'quantitiy'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'finish', 'format'], 'string', 'max' => 20],
            [['filename'], 'string', 'max' => 80],
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
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'filename' => Yii::t('store', 'Filename'),
            'finish' => Yii::t('store', 'Finish'),
            'profile_bool' => Yii::t('store', 'Profile Bool'),
            'quantitiy' => Yii::t('store', 'Quantitiy'),
            'format' => Yii::t('store', 'Format'),
            'comment' => Yii::t('store', 'Comment'),
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