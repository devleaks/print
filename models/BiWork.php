<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_work".
 *
 * @property string $document_type
 * @property string $document_status
 * @property string $document_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $total_price_htva
 * @property integer $document_line
 * @property string $line_price_htva
 * @property string $line_item_name
 * @property string $item_categorie
 * @property string $item_yii_category
 * @property string $work_item_name
 * @property string $task_name
 * @property string $work_status
 * @property string $work_line_status
 * @property integer $position
 * @property string $date_start
 * @property string $date_finish
 * @property integer $duration

CREATE or replace VIEW bi_work
AS SELECT
   d.document_type as document_type,
   d.status as document_status,
   d.name as document_name,
   d.created_at AS created_at,
   d.updated_at AS updated_at,
   d.price_htva AS total_price_htva,
   1 + dl.id - (select min(id) from document_line where document_id = d.id) as document_line,
   (ifnull(dl.price_htva,0)+ifnull(dl.extra_htva,0)) AS line_price_htva,
   i2.libelle_court AS line_item_name,
   i2.categorie AS item_categorie,
   i2.yii_category AS item_yii_category,
   i.libelle_court AS work_item_name,
   t.name AS task_name,
   w.status as work_status,
   wl.status as work_line_status,
   wl.position as position,
   wl.created_at AS date_start,
   wl.updated_at AS date_finish,
   (UNIX_TIMESTAMP(wl.updated_at) - UNIX_TIMESTAMP(wl.created_at)) as duration   
 FROM work_line wl,
 	  document_line dl,
      work w,
      document d,
      item i,
      item i2,
      task t
where wl.work_id = w.id
  and wl.document_line_id = dl.id
  and w.document_id = d.id
  and wl.item_id = i.id
  and dl.item_id = i2.id
  and wl.task_id = t.id

 */
class BiWork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bi_work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_name'], 'required'],
            [['created_at', 'updated_at', 'date_start', 'date_finish'], 'safe'],
            [['total_price_htva', 'line_price_htva'], 'number'],
            [['document_line', 'position', 'duration'], 'integer'],
            [['document_type', 'document_status', 'document_name', 'item_categorie', 'item_yii_category', 'work_status', 'work_line_status'], 'string', 'max' => 20],
            [['line_item_name', 'work_item_name'], 'string', 'max' => 40],
            [['task_name'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_type' => 'Document Type',
            'document_status' => 'Document Status',
            'document_name' => 'Document Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'total_price_htva' => 'Total Price Htva',
            'document_line' => 'Document Line',
            'line_price_htva' => 'Line Price Htva',
            'line_item_name' => 'Line Item Name',
            'item_categorie' => 'Item Categorie',
            'item_yii_category' => 'Item Yii Category',
            'work_item_name' => 'Work Item Name',
            'task_name' => 'Task Name',
            'work_status' => 'Work Status',
            'work_line_status' => 'Work Line Status',
            'position' => 'Position',
            'date_start' => 'Date Start',
            'date_finish' => 'Date Finish',
            'duration' => 'Duration',
        ];
    }
}
