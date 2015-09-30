<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "website_order".
 *
 * @property integer $id
 * @property string $order_date
 * @property string $name
 * @property string $company
 * @property string $address
 * @property string $city
 * @property string $vat
 * @property string $phone
 * @property string $email
 * @property string $promocode
 * @property string $comment
 * @property string $rawjson
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_name
 * @property string $clientcode
 *
 * @property WebsiteOrderLine[] $websiteOrderLines
 */
class _WebsiteOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'website_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rawjson', 'order_name'], 'required'],
            [['rawjson'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_date', 'vat', 'phone', 'promocode', 'clientcode'], 'string', 'max' => 40],
            [['name', 'company', 'city', 'email', 'order_name'], 'string', 'max' => 80],
            [['address', 'comment'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 20],
            [['order_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'order_date' => Yii::t('store', 'Order Date'),
            'name' => Yii::t('store', 'Name'),
            'company' => Yii::t('store', 'Company'),
            'address' => Yii::t('store', 'Address'),
            'city' => Yii::t('store', 'City'),
            'vat' => Yii::t('store', 'Vat'),
            'phone' => Yii::t('store', 'Phone'),
            'email' => Yii::t('store', 'Email'),
            'promocode' => Yii::t('store', 'Promocode'),
            'comment' => Yii::t('store', 'Comment'),
            'rawjson' => Yii::t('store', 'Rawjson'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'order_name' => Yii::t('store', 'Order Name'),
            'clientcode' => Yii::t('store', 'Clientcode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteOrderLines()
    {
        return $this->hasMany(WebsiteOrderLine::className(), ['website_order_id' => 'id']);
    }
}
