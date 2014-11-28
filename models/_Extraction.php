<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extraction".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $extraction_type
 * @property string $date_from
 * @property string $date_to
 * @property integer $order_from
 * @property integer $order_to
 *
 * @property Order $orderTo
 * @property Order $orderFrom
 * @property ExtractionLine[] $extractionLines
 */
class _Extraction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extraction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'date_from', 'date_to'], 'safe'],
            [['order_from', 'order_to'], 'integer'],
            [['extraction_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'extraction_type' => Yii::t('store', 'Extraction Type'),
            'date_from' => Yii::t('store', 'Date From'),
            'date_to' => Yii::t('store', 'Date To'),
            'order_from' => Yii::t('store', 'Order From'),
            'order_to' => Yii::t('store', 'Order To'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTo()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFrom()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtractionLines()
    {
        return $this->hasMany(ExtractionLine::className(), ['extraction_id' => 'id']);
    }
}
