<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_line".
 *
 * @property string $document_type
 * @property string $document_name
 * @property string $created_at
 * @property double $work_width
 * @property double $work_height
 * @property string $unit_price
 * @property double $quantity
 * @property string $extra_type
 * @property string $extra_amount
 * @property string $extra_htva
 * @property string $price_htva
 * @property string $total_htva
 * @property string $item_name
 * @property string $categorie
 * @property string $yii_category
 * @property string $comptabilite


CREATE or replace VIEW bi_line
AS SELECT
   d.document_type AS document_type,
   d.status AS document_status,
   d.name AS document_name,
   date_format(dl.created_at,'%Y-%m-%dT%TZ') AS created_at,
   dl.work_width AS work_width,
   dl.work_height AS work_height,
   dl.unit_price AS unit_price,
   dl.quantity AS quantity,
   dl.extra_type AS extra_type,
   dl.extra_amount AS extra_amount,
   dl.extra_htva AS extra_htva,
   dl.price_htva AS price_htva,
   (dl.price_htva + ifnull(dl.extra_htva,0)) AS total_htva,
   i.libelle_court AS item_name,
   i.categorie AS categorie,
   i.yii_category AS yii_category,
   i.comptabilite AS comptabilite
 FROM document_line dl,
      document d,
      item i
where (dl.document_id = d.id)
  and (dl.item_id = i.id)
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
            [['document_name', 'quantity'], 'required'],
            [['work_width', 'work_height', 'unit_price', 'quantity', 'extra_amount', 'extra_htva', 'price_htva', 'total_htva'], 'number'],
            [['document_type', 'document_name', 'created_at', 'extra_type', 'categorie', 'yii_category', 'comptabilite'], 'string', 'max' => 20],
            [['item_name'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_type' => 'Document Type',
            'document_name' => 'Document Name',
            'created_at' => 'Created At',
            'work_width' => 'Work Width',
            'work_height' => 'Work Height',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
            'extra_type' => 'Extra Type',
            'extra_amount' => 'Extra Amount',
            'extra_htva' => 'Extra Htva',
            'price_htva' => 'Price Htva',
            'total_htva' => 'Total Htva',
            'item_name' => 'Item Name',
            'categorie' => 'Categorie',
            'yii_category' => 'Yii Category',
            'comptabilite' => 'Comptabilite',
        ];
    }
}
