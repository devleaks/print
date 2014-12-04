<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "option".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $option_type
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $categorie
 * @property string $label
 *
 * @property ItemOption[] $itemOptions
 * @property Item $item
 * @property DocumentLineOption[] $orderLineOptions
 */
class _Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'option_type', 'name', 'label'], 'required'],
            [['item_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['option_type', 'status', 'name', 'categorie', 'label'], 'string', 'max' => 20],
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
            'item_id' => Yii::t('store', 'Item ID'),
            'option_type' => Yii::t('store', 'Option Type'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'name' => Yii::t('store', 'Name'),
            'categorie' => Yii::t('store', 'Categorie'),
            'label' => Yii::t('store', 'Label'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemOptions()
    {
        return $this->hasMany(ItemOption::className(), ['option_id' => 'id']);
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
    public function getDocumentLineOptions()
    {
        return $this->hasMany(DocumentLineOption::className(), ['option_id' => 'id']);
    }
}
