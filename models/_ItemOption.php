<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_option".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $option_id
 * @property integer $position
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $mandatory
 *
 * @property Option $option
 * @property Item $item
 */
class _ItemOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'option_id'], 'required'],
            [['item_id', 'option_id', 'position', 'mandatory'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
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
            'item_id' => Yii::t('store', 'Item ID'),
            'option_id' => Yii::t('store', 'Option ID'),
            'position' => Yii::t('store', 'Position'),
            'note' => Yii::t('store', 'Note'),
            'status' => Yii::t('store', 'Status'),
            'created_at' => Yii::t('store', 'Created At'),
            'updated_at' => Yii::t('store', 'Updated At'),
            'mandatory' => Yii::t('store', 'Mandatory'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
