<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pdf".
 *
 * @property integer $id
 * @property string $document_type
 * @property integer $document_id
 * @property integer $client_id
 * @property string $filename
 * @property string $created_at
 * @property string $updated_at
 * @property string $sent_at
 *
 * @property Client $client
 * @property Document $document
 */
class _Pdf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id', 'client_id'], 'integer'],
            [['filename'], 'required'],
            [['created_at', 'updated_at', 'sent_at'], 'safe'],
            [['document_type'], 'string', 'max' => 40],
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
            'document_type' => Yii::t('store', 'Document Type'),
            'document_id' => Yii::t('store', 'Document'),
            'client_id' => Yii::t('store', 'Client'),
            'filename' => Yii::t('store', 'Filename'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'sent_at' => Yii::t('store', 'Sent At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }
}
