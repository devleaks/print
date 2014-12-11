<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work_line".
 *
 * @property integer $id
 * @property integer $work_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $status
 * @property string $note
 * @property integer $task_id
 * @property integer $position
 * @property integer $item_id
 * @property string $due_date
 * @property integer $document_line_id
 * @property integer $priority
 *
 * @property User $updatedBy
 * @property Work $work
 * @property Task $task
 * @property Item $item
 * @property DocumentLine $documentLine
 * @property User $createdBy
 */
class _WorkLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_id', 'task_id', 'item_id', 'due_date', 'document_line_id'], 'required'],
            [['work_id', 'created_by', 'updated_by', 'task_id', 'position', 'item_id', 'document_line_id', 'priority'], 'integer'],
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['note'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'work_id' => Yii::t('store', 'Work ID'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_by' => Yii::t('store', 'Updated By'),
            'status' => Yii::t('store', 'Status'),
            'note' => Yii::t('store', 'Note'),
            'task_id' => Yii::t('store', 'Task ID'),
            'position' => Yii::t('store', 'Position'),
            'item_id' => Yii::t('store', 'Item ID'),
            'due_date' => Yii::t('store', 'Due Date'),
            'document_line_id' => Yii::t('store', 'Document Line ID'),
            'priority' => Yii::t('store', 'Priority'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWork()
    {
        return $this->hasOne(Work::className(), ['id' => 'work_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLine()
    {
        return $this->hasOne(DocumentLine::className(), ['id' => 'document_line_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
