<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chute".
 *
 * @property integer $id
 * @property string $created_at
 * @property double $work_length
 * @property string $note
 */
class _Chute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'work_length'], 'required'],
            [['created_at'], 'safe'],
            [['work_length'], 'number'],
            [['note'], 'string', 'max' => 20]
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
            'work_length' => Yii::t('store', 'Work Length'),
            'note' => Yii::t('store', 'Note'),
        ];
    }
}
