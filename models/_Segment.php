<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "segment".
 *
 * @property integer $id
 * @property integer $document_line_id
 * @property double $work_length
 * @property string $created_at
 * @property string $updated_at
 * @property integer $master_id
 *
 * @property Master $master
 * @property DocumentLine $documentLine
 */
class _Segment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'segment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_line_id', 'work_length'], 'required'],
            [['document_line_id', 'master_id'], 'integer'],
            [['work_length'], 'number'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'document_line_id' => Yii::t('store', 'Document Line ID'),
            'work_length' => Yii::t('store', 'Work Length'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'master_id' => Yii::t('store', 'Master ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaster()
    {
        return $this->hasOne(Master::className(), ['id' => 'master_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLine()
    {
        return $this->hasOne(DocumentLine::className(), ['id' => 'document_line_id']);
    }
}
