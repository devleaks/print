<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $name
 * @property string $date_from
 * @property string $date_to
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class _Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_from', 'date_to', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
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
            'name' => Yii::t('store', 'Name'),
            'date_from' => Yii::t('store', 'Date From'),
            'date_to' => Yii::t('store', 'Date To'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }
}
