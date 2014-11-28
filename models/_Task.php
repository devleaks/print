<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $name
 * @property string $note
 * @property double $first_run
 * @property double $next_run
 * @property double $unit_cost
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $icon
 *
 * @property ItemTask[] $itemTasks
 * @property WorkLine[] $workLines
 */
class _Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_run', 'next_run', 'unit_cost'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['note'], 'string', 'max' => 160],
            [['status'], 'string', 'max' => 20],
            [['icon'], 'string', 'max' => 40]
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
            'note' => Yii::t('store', 'Note'),
            'first_run' => Yii::t('store', 'First Run'),
            'next_run' => Yii::t('store', 'Next Run'),
            'unit_cost' => Yii::t('store', 'Unit Cost'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'icon' => Yii::t('store', 'Icon'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemTasks()
    {
        return $this->hasMany(ItemTask::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['task_id' => 'id']);
    }
}
