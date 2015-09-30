<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "website_order_line".
 */
class WebsiteOrderLine extends _WebsiteOrderLine
{
	const PROMOCODE = 'PM001-';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }


	protected function getChromaType() {
		$str = strtolower($this->finish);
		if(in_array($str, ['mat', 'mate', 'matte'])) {
			return Item::findOne(['reference' => 'ChromaWHITEMAT']);
		} else {
			return Item::findOne(['reference' => 'ChromaWHITEGLOSSY']);
		}
	}
/*
INSERT INTO `item` (`id`, `yii_category`, `comptabilite`, `reference`, `libelle_court`, `libelle_long`, `categorie`, `prix_de_vente`, `taux_de_tva`, `status`, `type_travaux_photos`, `type_numerique`, `fournisseur`, `reference_fournisseur`, `conditionnement`, `prix_d_achat_de_reference`, `client`, `quantite`, `date_initiale`, `date_finale`, `suivi_de_stock`, `reassort_possible`, `seuil_de_commande`, `site_internet`, `creation`, `mise_a_jour`, `en_cours`, `stock`, `commentaires`, `identification`, `created_at`, `updated_at`)
VALUES
	(1050, 'Promo', '700200', 'PM001-40x60', 'ChromaLuxe 40x60 Promo', 'CL40x60PROMO', 'ChromaLuxe', 45.00, 21.00, 'ACTIVE', 'Divers', 'Divers', 'Divers ', '-', '1', '0', 'Prix de vente ordinaire ', 1, '2000-01-01', '2099-12-31', 'Faux', 'Faux', '0', 'Faux', '2013-07-10', '2013-07-10', 'Vrai', '0', '', NULL, NULL, NULL);
*/
	public function createOrderLine($order) {
		$ok = true;
		$item = Item::findOne(['reference' => self::PROMOCODE.$this->format]);
		$sizes = explode('x', strtolower($this->format));
		
		$dl = new DocumentLine([
			'document_id' => $order->id,
			'item_id' => $item->id,
			'quantity' => $this->quantity,
			'work_width' => $sizes[0],
			'work_height' => $sizes[1],
			'unit_price' => $item->prix_de_vente,
			'vat' => $item->taux_de_tva,
			'due_date' => $order->due_date,
		]);
		$dl->updatePrice();
		if(!$dl->save()) {
			Yii::trace(print_r($dl->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		
		$detail = new DocumentLineDetail();
		$detail->document_line_id = $dl->id;
		
		if($this->profile_bool) {
			$renfort_item = Item::findOne(['reference' => 'Renfort']);
			$detail->renfort_id = $renfort_item->id;
		}
		$finish_item = $this->getChromaType();
		$detail->chroma_id = $finish_item->id;
		
		if(!$detail->save()) {
			Yii::trace(print_r($detail->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		return $ok;
	}

}
