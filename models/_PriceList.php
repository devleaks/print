<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price_list".
 *
 * @property integer $id
 * @property string $name
 * @property string $note
 * @property string $sizes
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PriceListItem[] $priceListItems
 */
class _PriceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['note'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 20],
            [['sizes'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'name' => Yii::t('store', 'Name'),
            'note' => Yii::t('store', 'Note'),
            'sizes' => Yii::t('store', 'Sizes'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceListItems()
    {
        return $this->hasMany(PriceListItem::className(), ['price_list_id' => 'id']);
    }
}
