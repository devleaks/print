<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $order_type
 * @property integer $parent_id
 * @property string $name
 * @property integer $client_id
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property double $price_htva
 * @property double $price_tvac
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $vat_bool
 * @property string $lang
 * @property string $reference
 * @property string $reference_client
 * @property string $due_date
 * @property double $vat
 * @property integer $bom_bool
 *
 * @property Extraction[] $extractions
 * @property User $updatedBy
 * @property _Document $parent
 * @property _Document[] $orders
 * @property Client $client
 * @property User $createdBy
 * @property OrderLine[] $orderLines
 * @property Work[] $works
 */
class _Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'client_id', 'created_by', 'updated_by', 'vat_bool', 'bom_bool'], 'integer'],
            [['name', 'client_id', 'due_date'], 'required'],
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['price_htva', 'price_tvac', 'vat'], 'number'],
            [['order_type', 'name', 'status', 'lang'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 160],
            [['reference', 'reference_client'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'order_type' => Yii::t('store', 'Order Type'),
            'parent_id' => Yii::t('store', 'Parent ID'),
            'name' => Yii::t('store', 'Name'),
            'client_id' => Yii::t('store', 'Client ID'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'price_tvac' => Yii::t('store', 'Price Tvac'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_by' => Yii::t('store', 'Updated By'),
            'vat_bool' => Yii::t('store', 'Vat Bool'),
            'lang' => Yii::t('store', 'Lang'),
            'reference' => Yii::t('store', 'Reference'),
            'reference_client' => Yii::t('store', 'Reference Client'),
            'due_date' => Yii::t('store', 'Due Date'),
            'vat' => Yii::t('store', 'Vat'),
            'bom_bool' => Yii::t('store', 'Bom Bool'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtractions()
    {
        return $this->hasMany(Extraction::className(), ['order_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(_Order::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(_Order::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderLines()
    {
        return $this->hasMany(OrderLine::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorks()
    {
        return $this->hasMany(Work::className(), ['order_id' => 'id']);
    }
}
