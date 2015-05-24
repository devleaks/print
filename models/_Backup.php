<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backup".
 *
 * @property integer $id
 * @property string $filename
 * @property string $note
 * @property string $status
 * @property string $created_at
 */
class _Backup extends \yii\db\ActiveRecord
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
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['filename'], 'string', 'max' => 250],
            [['note'], 'string', 'max' => 160],
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
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
        ];
    }
}
