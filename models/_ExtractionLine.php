<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extraction_line".
 *
 * @property integer $id
 * @property integer $extraction_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $object_type
 * @property integer $object_id
 *
 * @property Extraction $object
 * @property Extraction $extraction
 */
class _ExtractionLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extraction_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['extraction_id', 'object_id'], 'required'],
            [['extraction_id', 'object_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'object_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'extraction_id' => Yii::t('store', 'Extraction ID'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'object_type' => Yii::t('store', 'Object Type'),
            'object_id' => Yii::t('store', 'Object ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Extraction::className(), ['id' => 'object_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtraction()
    {
        return $this->hasOne(Extraction::className(), ['id' => 'extraction_id']);
    }
}
