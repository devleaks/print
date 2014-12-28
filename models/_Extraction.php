<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extraction".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $extraction_type
 * @property string $date_from
 * @property string $date_to
 * @property integer $document_from
 * @property integer $document_to
 * @property string $extraction_method
 *
 * @property Document $documentTo
 * @property Document $documentFrom
 * @property ExtractionLine[] $extractionLines
 */
class _Extraction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extraction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'date_from', 'date_to'], 'safe'],
            [['document_from', 'document_to'], 'integer'],
            [['extraction_type', 'extraction_method'], 'string', 'max' => 20]
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
            'extraction_type' => Yii::t('store', 'Extraction Type'),
            'date_from' => Yii::t('store', 'Date From'),
            'date_to' => Yii::t('store', 'Date To'),
            'document_from' => Yii::t('store', 'Document From'),
            'document_to' => Yii::t('store', 'Document To'),
            'extraction_method' => Yii::t('store', 'Extraction Method'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTo()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentFrom()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtractionLines()
    {
        return $this->hasMany(ExtractionLine::className(), ['extraction_id' => 'id']);
    }
}
