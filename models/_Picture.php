<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "picture".
 *
 * @property integer $id
 * @property string $name
 * @property integer $document_line_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $mimetype
 * @property string $filename
 *
 * @property DocumentLine $orderLine
 */
class _Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'picture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'document_line_id', 'mimetype', 'filename'], 'required'],
            [['document_line_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'mimetype'], 'string', 'max' => 80],
            [['filename'], 'string', 'max' => 255]
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
            'document_line_id' => Yii::t('store', 'Order Line ID'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'mimetype' => Yii::t('store', 'Mimetype'),
            'filename' => Yii::t('store', 'Filename'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLine()
    {
        return $this->hasOne(DocumentLine::className(), ['id' => 'document_line_id']);
    }
}
