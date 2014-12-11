<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $status
 * @property string $due_date
 * @property integer $document_id
 * @property integer $priority
 *
 * @property User $updatedBy
 * @property Document $document
 * @property User $createdBy
 * @property WorkLine[] $workLines
 */
class _Work extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['created_by', 'updated_by', 'document_id', 'priority'], 'integer'],
            [['due_date', 'document_id'], 'required'],
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
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'created_by' => Yii::t('store', 'Created By'),
            'updated_by' => Yii::t('store', 'Updated By'),
            'status' => Yii::t('store', 'Status'),
            'due_date' => Yii::t('store', 'Due Date'),
            'document_id' => Yii::t('store', 'Document ID'),
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
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkLines()
    {
        return $this->hasMany(WorkLine::className(), ['work_id' => 'id']);
    }
}
