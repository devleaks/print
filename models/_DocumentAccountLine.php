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
class _DocumentAccountLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document_account_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_id'], 'required'],
            [['document_id'], 'integer'],
            [['vat', 'total_price_htva', 'total_extra_htva'], 'number'],
            [['comptabilite'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => Yii::t('store', 'Document ID'),
            'comptabilite' => Yii::t('store', 'Comptabilite'),
            'vat' => Yii::t('store', 'Vat'),
            'total_price_htva' => Yii::t('store', 'Total Price Htva'),
            'total_extra_htva' => Yii::t('store', 'Total Extra Htva'),
        ];
    }
}
