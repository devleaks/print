<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_task".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $task_id
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $position
 *
 * @property Task $task
 * @property Item $item
 */
class _ItemTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'task_id', 'position'], 'required'],
            [['item_id', 'task_id', 'position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 20],
            [['item_id', 'task_id'], 'unique', 'targetAttribute' => ['item_id', 'task_id'], 'message' => 'The combination of Item ID and Task ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'item_id' => Yii::t('store', 'Item ID'),
            'task_id' => Yii::t('store', 'Task ID'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'position' => Yii::t('store', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
