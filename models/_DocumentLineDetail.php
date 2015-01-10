<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_line_detail".
 *
 * @property integer $id
 * @property integer $document_line_id
 * @property string $note
 * @property integer $chroma_id
 * @property double $price_chroma
 * @property integer $corner_bool
 * @property double $price_corner
 * @property integer $renfort_bool
 * @property double $price_renfort
 * @property integer $frame_id
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
 * @property integer $chassis_id
 * @property double $price_chassis
 * @property integer $filmuv_bool
 * @property double $price_filmuv
 *
 * @property Item $finish
 * @property Item $chroma
 * @property Item $frame
 * @property Item $chassis
 * @property Item $support
 * @property Item $tirage
 * @property Item $collage
 * @property Item $protection
 * @property DocumentLine $documentLine
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
            [['document_line_id'], 'required'],
            [['document_line_id', 'chroma_id', 'corner_bool', 'renfort_bool', 'frame_id', 'montage_bool', 'finish_id', 'support_id', 'tirage_id', 'collage_id', 'protection_id', 'chassis_id', 'filmuv_bool'], 'integer'],
            [['price_chroma', 'price_corner', 'price_renfort', 'price_frame', 'price_montage', 'price_support', 'price_tirage', 'price_collage', 'price_protection', 'price_chassis', 'price_filmuv'], 'number'],
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
            'document_line_id' => Yii::t('store', 'Document Line'),
            'note' => Yii::t('store', 'Note'),
            'chroma_id' => Yii::t('store', 'Chroma'),
            'price_chroma' => Yii::t('store', 'Price Chroma'),
            'corner_bool' => Yii::t('store', 'Corner Bool'),
            'price_corner' => Yii::t('store', 'Price Corner'),
            'renfort_bool' => Yii::t('store', 'Renfort Bool'),
            'price_renfort' => Yii::t('store', 'Price Renfort'),
            'frame_id' => Yii::t('store', 'Frame'),
            'price_frame' => Yii::t('store', 'Price Frame'),
            'montage_bool' => Yii::t('store', 'Montage Bool'),
            'price_montage' => Yii::t('store', 'Price Montage'),
            'finish_id' => Yii::t('store', 'Finish'),
            'support_id' => Yii::t('store', 'Support'),
            'price_support' => Yii::t('store', 'Price Support'),
            'tirage_id' => Yii::t('store', 'Tirage'),
            'price_tirage' => Yii::t('store', 'Price Tirage'),
            'collage_id' => Yii::t('store', 'Collage'),
            'price_collage' => Yii::t('store', 'Price Collage'),
            'protection_id' => Yii::t('store', 'Protection'),
            'price_protection' => Yii::t('store', 'Price Protection'),
            'chassis_id' => Yii::t('store', 'Chassis'),
            'price_chassis' => Yii::t('store', 'Price Chassis'),
            'filmuv_bool' => Yii::t('store', 'Filmuv Bool'),
            'price_filmuv' => Yii::t('store', 'Price Filmuv'),
        ];
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
    public function getChassis()
    {
        return $this->hasOne(Item::className(), ['id' => 'chassis_id']);
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
}
