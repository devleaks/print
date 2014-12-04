<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_line_detail".
 *
 * @property integer $id
 * @property integer $chroma_id
 * @property double $price_chroma
 * @property integer $corner_bool
 * @property double $price_corner
 * @property integer $renfort_bool
 * @property double $price_renfort
 * @property double $price_frame
 * @property integer $montage_bool
 * @property double $price_montage
 * @property integer $finish_id
 * @property integer $support_id
 * @property double $price_support
 * @property integer $tirage_id
 * @property double $price_tirage
 * @property integer $collage_id
 * @property double $price_collage
 * @property integer $protection_id
 * @property double $price_protection
 * @property string $note
 * @property integer $document_line_id
 * @property integer $frame_id
 *
 * @property Item $protection
 * @property DocumentLine $documentLine
 * @property Item $chroma
 * @property Item $frame
 * @property Item $finish
 * @property Item $support
 * @property Item $tirage
 * @property Item $collage
 */
class _DocumentLineDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_line_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chroma_id', 'corner_bool', 'renfort_bool', 'montage_bool', 'finish_id', 'support_id', 'tirage_id', 'collage_id', 'protection_id', 'document_line_id', 'frame_id'], 'integer'],
            [['price_chroma', 'price_corner', 'price_renfort', 'price_frame', 'price_montage', 'price_support', 'price_tirage', 'price_collage', 'price_protection'], 'number'],
            [['document_line_id'], 'required'],
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
            'chroma_id' => Yii::t('store', 'Chroma ID'),
            'price_chroma' => Yii::t('store', 'Price Chroma'),
            'corner_bool' => Yii::t('store', 'Corner Bool'),
            'price_corner' => Yii::t('store', 'Price Corner'),
            'renfort_bool' => Yii::t('store', 'Renfort Bool'),
            'price_renfort' => Yii::t('store', 'Price Renfort'),
            'price_frame' => Yii::t('store', 'Price Frame'),
            'montage_bool' => Yii::t('store', 'Montage Bool'),
            'price_montage' => Yii::t('store', 'Price Montage'),
            'finish_id' => Yii::t('store', 'Finish ID'),
            'support_id' => Yii::t('store', 'Support ID'),
            'price_support' => Yii::t('store', 'Price Support'),
            'tirage_id' => Yii::t('store', 'Tirage ID'),
            'price_tirage' => Yii::t('store', 'Price Tirage'),
            'collage_id' => Yii::t('store', 'Collage ID'),
            'price_collage' => Yii::t('store', 'Price Collage'),
            'protection_id' => Yii::t('store', 'Protection ID'),
            'price_protection' => Yii::t('store', 'Price Protection'),
            'note' => Yii::t('store', 'Note'),
            'document_line_id' => Yii::t('store', 'Document Line ID'),
            'frame_id' => Yii::t('store', 'Frame ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProtection()
    {
        return $this->hasOne(Item::className(), ['id' => 'protection_id']);
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
    public function getChroma()
    {
        return $this->hasOne(Item::className(), ['id' => 'chroma_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrame()
    {
        return $this->hasOne(Item::className(), ['id' => 'frame_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinish()
    {
        return $this->hasOne(Item::className(), ['id' => 'finish_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupport()
    {
        return $this->hasOne(Item::className(), ['id' => 'support_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTirage()
    {
        return $this->hasOne(Item::className(), ['id' => 'tirage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollage()
    {
        return $this->hasOne(Item::className(), ['id' => 'collage_id']);
    }
}
