<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_account_line".
 *
 * @property integer $document_id
 * @property string $comptabilite
 * @property string $vat
 * @property string $total_price_htva
 * @property string $total_extra_htva
 */
class DocumentAccountLine extends _DocumentAccountLine
{
	public $position;
	
    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
			[['position'], 'number'],
		]);
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
	        'position' => Yii::t('store', 'Position'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
    }

}
