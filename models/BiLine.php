<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_line".
 *
 * @property string $document_type
 * @property string $date_year
 * @property string $date_month
 * @property string $pays
 * @property string $lang
 * @property double $work_width
 * @property double $work_height
 * @property string $unit_price
 * @property double $quantity
 * @property string $extra_type
 * @property string $extra_amount
 * @property string $extra_htva
 * @property string $price_htva
 * @property integer $item_id
 * @property string $categorie
 * @property string $yii_category
 * @property string $comptabilite
 */
class BiLine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bi_line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_width', 'work_height', 'unit_price', 'quantity', 'extra_amount', 'extra_htva', 'price_htva'], 'number'],
            [['quantity'], 'required'],
            [['item_id'], 'integer'],
            [['document_type', 'lang', 'extra_type', 'categorie', 'yii_category', 'comptabilite'], 'string', 'max' => 20],
            [['date_year'], 'string', 'max' => 4],
            [['date_month'], 'string', 'max' => 2],
            [['pays'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_type' => 'Document Type',
            'date_year' => 'Date Year',
            'date_month' => 'Date Month',
            'pays' => 'Pays',
            'lang' => 'Lang',
            'work_width' => 'Work Width',
            'work_height' => 'Work Height',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
            'extra_type' => 'Extra Type',
            'extra_amount' => 'Extra Amount',
            'extra_htva' => 'Extra Htva',
            'price_htva' => 'Price Htva',
            'item_id' => 'Item ID',
            'categorie' => 'Categorie',
            'yii_category' => 'Yii Category',
            'comptabilite' => 'Comptabilite',
        ];
    }
}
