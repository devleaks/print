<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_line".
 *
 * @property string $document_type
 * @property string $document_status
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
 * @property string $det_price_chassis
 * @property string $det_price_chroma
 * @property string $det_price_collage
 * @property string $det_price_corner
 * @property string $det_price_filmuv
 * @property string $det_price_frame
 * @property string $det_price_montage
 * @property string $det_price_protection
 * @property string $det_price_renfort
 * @property string $det_price_support
 * @property string $det_price_tirage
 * @property integer $det_corner_bool
 * @property integer $det_filmuv_bool
 * @property integer $det_montage_bool
 * @property integer $det_renfort_bool
 * @property string $det_tirage_factor
 * @property integer $det_chassis_id
 * @property integer $det_chroma_id
 * @property integer $det_collage_id
 * @property integer $det_finish_id
 * @property integer $det_frame_id
 * @property integer $det_protection_id
 * @property integer $det_renfort_id
 * @property integer $det_support_id
 * @property integer $det_tirage_id

 create or replace view bi_line
as select
   d.document_type as document_type,
   d.status as document_status,
   d.name as document_name,
   date_format(dl.created_at,'%Y-%m-%dT%TZ') as created_at,
   dl.work_width as work_width,
   dl.work_height as work_height,
   dl.unit_price as unit_price,
   dl.quantity as quantity,
   dl.extra_type as extra_type,
   dl.extra_amount as extra_amount,
   dl.extra_htva as extra_htva,
   dl.price_htva as price_htva,
   (dl.price_htva + ifnull(dl.extra_htva,0)) as total_htva,
   i.libelle_court as item_name,
   i.categorie as categorie,
   i.yii_category as yii_category,
   i.comptabilite as comptabilite
 from document_line dl,
      document d,
      item i
where (dl.document_id = d.id)
  and (dl.item_id = i.id)

******* FULL DETAIL:

create or replace view bi_line
as select
    d.document_type as document_type,
    d.status as document_status,
    d.name as document_name,
    date_format(dl.created_at,'%Y-%m-%dT%TZ') as created_at,
    dl.work_width as work_width,
    dl.work_height as work_height,
    dl.unit_price as unit_price,
    dl.quantity as quantity,
    dl.extra_type as extra_type,
    dl.extra_amount as extra_amount,
    dl.extra_htva as extra_htva,
    dl.price_htva as price_htva,
    (dl.price_htva + ifnull(dl.extra_htva,0)) as total_htva,
    i.libelle_court as item_name,
    i.categorie as categorie,
    i.yii_category as yii_category,
    i.comptabilite as comptabilite,
    det.chroma_id as det_chroma_id,
    det.price_chroma as det_price_chroma,
    det.corner_bool as det_corner_bool,
    det.price_corner as det_price_corner,
    det.renfort_bool as det_renfort_bool,
    det.price_renfort as det_price_renfort,
    det.frame_id as det_frame_id,
    det.price_frame as det_price_frame,
    det.montage_bool as det_montage_bool,
    det.price_montage as det_price_montage,
    det.finish_id as det_finish_id,
    det.support_id as det_support_id,
    det.price_support as det_price_support,
    det.tirage_id as det_tirage_id,
    det.price_tirage as det_price_tirage,
    det.collage_id as det_collage_id,
    det.price_collage as det_price_collage,
    det.protection_id as det_protection_id,
    det.price_protection as det_price_protection,
    det.chassis_id as det_chassis_id,
    det.price_chassis as det_price_chassis,
    det.filmuv_bool as det_filmuv_bool,
    det.price_filmuv as det_price_filmuv,
    det.tirage_factor as det_tirage_factor,
    det.renfort_id as det_renfort_id
 from document_line dl left join document_line_detail det on dl.id = det.document_line_id,
      document d,
      item i
where (dl.document_id = d.id)
  and (dl.item_id = i.id)
  
 */
class BiItem extends \yii\db\ActiveRecord
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
            [['work_width', 'work_height', 'unit_price', 'quantity', 'extra_amount', 'extra_htva', 'price_htva', 'total_htva', 'det_price_chassis', 'det_price_chroma', 'det_price_collage', 'det_price_corner', 'det_price_filmuv', 'det_price_frame', 'det_price_montage', 'det_price_protection', 'det_price_renfort', 'det_price_support', 'det_price_tirage', 'det_tirage_factor'], 'number'],
            [['quantity'], 'required'],
            [['det_corner_bool', 'det_filmuv_bool', 'det_montage_bool', 'det_renfort_bool', 'det_chassis_id', 'det_chroma_id', 'det_collage_id', 'det_finish_id', 'det_frame_id', 'det_protection_id', 'det_renfort_id', 'det_support_id', 'det_tirage_id'], 'integer'],
            [['document_type', 'document_status', 'document_name', 'created_at', 'extra_type', 'categorie', 'yii_category', 'comptabilite'], 'string', 'max' => 20],
            [['item_name'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_type' => Yii::t('store', 'Document Type'),
            'document_status' => Yii::t('store', 'Document Status'),
            'document_name' => Yii::t('store', 'Document Name'),
            'created_at' => Yii::t('store', 'Created At'),
            'work_width' => Yii::t('store', 'Work Width'),
            'work_height' => Yii::t('store', 'Work Height'),
            'unit_price' => Yii::t('store', 'Unit Price'),
            'quantity' => Yii::t('store', 'Quantity'),
            'extra_type' => Yii::t('store', 'Extra Type'),
            'extra_amount' => Yii::t('store', 'Extra Amount'),
            'extra_htva' => Yii::t('store', 'Extra Htva'),
            'price_htva' => Yii::t('store', 'Price Htva'),
            'total_htva' => Yii::t('store', 'Total Htva'),
            'item_name' => Yii::t('store', 'Item Name'),
            'categorie' => Yii::t('store', 'Categorie'),
            'yii_category' => Yii::t('store', 'Yii Category'),
            'comptabilite' => Yii::t('store', 'Comptabilite'),
            'det_price_chassis' => Yii::t('store', 'Det Price Chassis'),
            'det_price_chroma' => Yii::t('store', 'Det Price Chroma'),
            'det_price_collage' => Yii::t('store', 'Det Price Collage'),
            'det_price_corner' => Yii::t('store', 'Det Price Corner'),
            'det_price_filmuv' => Yii::t('store', 'Det Price Filmuv'),
            'det_price_frame' => Yii::t('store', 'Det Price Frame'),
            'det_price_montage' => Yii::t('store', 'Det Price Montage'),
            'det_price_protection' => Yii::t('store', 'Det Price Protection'),
            'det_price_renfort' => Yii::t('store', 'Det Price Renfort'),
            'det_price_support' => Yii::t('store', 'Det Price Support'),
            'det_price_tirage' => Yii::t('store', 'Det Price Tirage'),
            'det_corner_bool' => Yii::t('store', 'Det Corner Bool'),
            'det_filmuv_bool' => Yii::t('store', 'Det Filmuv Bool'),
            'det_montage_bool' => Yii::t('store', 'Det Montage Bool'),
            'det_renfort_bool' => Yii::t('store', 'Det Renfort Bool'),
            'det_tirage_factor' => Yii::t('store', 'Det Tirage Factor'),
            'det_chassis_id' => Yii::t('store', 'Det Chassis ID'),
            'det_chroma_id' => Yii::t('store', 'Det Chroma ID'),
            'det_collage_id' => Yii::t('store', 'Det Collage ID'),
            'det_finish_id' => Yii::t('store', 'Det Finish ID'),
            'det_frame_id' => Yii::t('store', 'Det Frame ID'),
            'det_protection_id' => Yii::t('store', 'Det Protection ID'),
            'det_renfort_id' => Yii::t('store', 'Det Renfort ID'),
            'det_support_id' => Yii::t('store', 'Det Support ID'),
            'det_tirage_id' => Yii::t('store', 'Det Tirage ID'),
        ];
    }
}
