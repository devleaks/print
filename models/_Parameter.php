<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parameter".
 *
 * @property string $domain
 * @property string $name
 * @property string $lang
 * @property string $value_text
 * @property double $value_number
 * @property integer $value_int
 * @property string $value_date
 * @property string $created_at
 * @property string $updated_at
 */
class _Parameter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain', 'name', 'lang'], 'required'],
            [['value_number'], 'number'],
            [['value_int'], 'integer'],
            [['value_date', 'created_at', 'updated_at'], 'safe'],
            [['domain', 'lang'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 40],
            [['value_text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'domain' => Yii::t('store', 'Domain'),
            'name' => Yii::t('store', 'Name'),
            'lang' => Yii::t('store', 'Lang'),
            'value_text' => Yii::t('store', 'Value Text'),
            'value_number' => Yii::t('store', 'Value Number'),
            'value_int' => Yii::t('store', 'Value Int'),
            'value_date' => Yii::t('store', 'Value Date'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }
}
