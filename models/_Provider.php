<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class _Provider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 80],
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
            'email' => Yii::t('store', 'Email'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }
}
