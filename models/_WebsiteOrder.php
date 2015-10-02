<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "website_order".
 *
 * @property integer $id
 * @property integer $document_id
 * @property string $order_id
 * @property string $order_name
 * @property string $order_date
 * @property string $name
 * @property string $company
 * @property string $address
 * @property string $city
 * @property string $vat
 * @property string $phone
 * @property string $email
 * @property string $delivery
 * @property string $promocode
 * @property string $clientcode
 * @property string $comment
 * @property string $rawjson
 * @property string $convert_errors
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Document $document
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
            [['document_id'], 'integer'],
            [['order_name', 'rawjson'], 'required'],
            [['rawjson', 'convert_errors'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_id', 'order_date', 'vat', 'phone', 'delivery', 'promocode', 'clientcode'], 'string', 'max' => 40],
            [['order_name', 'name', 'company', 'city', 'email'], 'string', 'max' => 80],
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
            'document_id' => Yii::t('store', 'Document ID'),
            'order_id' => Yii::t('store', 'Order ID'),
            'order_name' => Yii::t('store', 'Order Name'),
            'order_date' => Yii::t('store', 'Order Date'),
            'name' => Yii::t('store', 'Name'),
            'company' => Yii::t('store', 'Company'),
            'address' => Yii::t('store', 'Address'),
            'city' => Yii::t('store', 'City'),
            'vat' => Yii::t('store', 'Vat'),
            'phone' => Yii::t('store', 'Phone'),
            'email' => Yii::t('store', 'Email'),
            'delivery' => Yii::t('store', 'Delivery'),
            'promocode' => Yii::t('store', 'Promocode'),
            'clientcode' => Yii::t('store', 'Clientcode'),
            'comment' => Yii::t('store', 'Comment'),
            'rawjson' => Yii::t('store', 'Rawjson'),
            'convert_errors' => Yii::t('store', 'Convert Errors'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteOrderLines()
    {
        return $this->hasMany(WebsiteOrderLine::className(), ['website_order_id' => 'id']);
    }
}
