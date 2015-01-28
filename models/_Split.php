<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "split".
 *
 * @property integer $id
 * @property integer $id1
 * @property integer $id2
 *
 * @property Segment $id20
 * @property Segment $id10
 */
class _Split extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'split';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id1', 'id2'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('store', 'ID'),
            'id1' => Yii::t('store', 'Id1'),
            'id2' => Yii::t('store', 'Id2'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId20()
    {
        return $this->hasOne(Segment::className(), ['document_line_id' => 'id2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId10()
    {
        return $this->hasOne(Segment::className(), ['id' => 'id1']);
    }
}
