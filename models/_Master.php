<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master".
 *
 * @property integer $id
 * @property double $work_length
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property integer $keep
 *
 * @property Segment[] $segments
 */
class _Master extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_length'], 'required'],
            [['work_length'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['keep'], 'integer'],
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
            'work_length' => Yii::t('store', 'Work Length'),
            'note' => Yii::t('store', 'Note'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'keep' => Yii::t('store', 'Keep'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegments()
    {
        return $this->hasMany(Segment::className(), ['master_id' => 'id']);
    }
}
