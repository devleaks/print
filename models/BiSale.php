<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bi_sale".
 *
 * @property string $document_type
 * @property string $document_status
 * @property string $created_at
 * @property string $updated_at
 * @property string $due_date
 * @property string $date_year
 * @property string $date_month
 * @property string $price_htva
 * @property string $client_fn
 * @property string $client_ln
 * @property string $client_an
 * @property string $country
 * @property string $language
 * @property integer $client_id

CREATE OR REPLACE VIEW bi_sale
AS SELECT
  d.document_type AS document_type,
  d.status AS document_status,
  d.created_at AS created_at,
  d.updated_at AS updated_at,
  d.due_date AS due_date,date_format(d.created_at,'%Y') AS date_year,date_format(d.created_at,'%m') AS date_month,
  d.price_htva AS price_htva,
  c.prenom AS client_fn,
  c.nom AS client_ln,
  c.autre_nom AS client_an,
  c.pays AS country,
  c.lang AS language,
  c.id AS client_id
FROM (document d join client c) where (d.client_id = c.id)

 */
class BiSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bi_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'due_date'], 'safe'],
            [['due_date', 'client_ln'], 'required'],
            [['price_htva'], 'number'],
            [['client_id'], 'integer'],
            [['document_type', 'document_status', 'language'], 'string', 'max' => 20],
            [['date_year'], 'string', 'max' => 4],
            [['date_month'], 'string', 'max' => 2],
            [['client_fn', 'client_ln', 'client_an', 'country'], 'string', 'max' => 80],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'due_date' => 'Due Date',
            'date_year' => 'Date Year',
            'date_month' => 'Date Month',
            'price_htva' => 'Price Htva',
            'client_fn' => 'Client Fn',
            'client_ln' => 'Client Ln',
            'client_an' => 'Client An',
            'country' => 'Country',
            'language' => 'Language',
            'client_id' => 'Client ID',
        ];
    }
}
