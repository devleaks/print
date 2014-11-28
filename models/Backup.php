<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "backup".
 *
 * @property integer $id
 * @property string $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Backup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backup';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//			[['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 250],
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
            'filename' => Yii::t('store', 'Filename'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
        ];
    }
}
