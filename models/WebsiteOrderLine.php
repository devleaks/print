<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "website_order_line".
 */
class WebsiteOrderLine extends _WebsiteOrderLine
{
	const PROMOCODE = 'PROMO-';

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
		switch(strtolower($this->finish)) {
			case 'zilver mat': return Item::findOne(['reference' => 'ChromaCLEARMAT']); break;
			case 'glanzend': return Item::findOne(['reference' => 'ChromaWHITEGLOSSY']); break;
			case 'mat': return Item::findOne(['reference' => 'ChromaWHITEMAT']); break;			
			return null;
		}
	}
	protected function getProfileType() {
		switch(strtolower($this->profile)) {
			case 'ja': return Item::findOne(['reference' => 'Renfort']); break;
			case 'pro': return Item::findOne(['reference' => 'RenfortPro']); break;
			default: return null; break;			
		}
	}


	public function createOrderLine($order) {
		$ok = true;
		$main_item = null;
		if(in_array($this->format, ['40x60', '50x50'])){
			$main_item = Item::findOne(['reference' => self::PROMOCODE.$this->format]);
		} else {
			$main_item = Item::findOne(['reference' => Item::TYPE_CHROMALUXE]);
		}
		if(!$main_item) {
			echo 'Could not find item.';
			exit(1);
		}
		$sizes = explode('x', strtolower($this->format));
		
		$dl = new DocumentLine([
			'document_id' => $order->id,
			'item_id' => $main_item->id,
			'quantity' => $this->quantity,
			'work_width' => $sizes[0],
			'work_height' => $sizes[1],
			'unit_price' => $main_item->prix_de_vente,
			'vat' => $main_item->taux_de_tva,
			'due_date' => $order->due_date,
		]);
		$dl->updatePrice();
		if(!$dl->save()) {
			Yii::trace(print_r($dl->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		
		$detail = new DocumentLineDetail();
		$detail->document_line_id = $dl->id;

		/** ChromaLuxe details */
		$finish_item = $this->getChromaType();
		$detail->chroma_id = $finish_item->id;

		$total_price = 0;
		if($main_item->reference == Item::TYPE_CHROMALUXE) {
			$pc = new ChromaLuxePriceCalculator();
			$detail->price_chroma = $pc->price($dl->work_width, $dl->work_height);
			$total_price += $detail->price_chroma;
		}

		/** Profile details */
		if($renfort_item = $this->getProfileType()) {
			$detail->renfort_id = $renfort_item->id;
			if($main_item->reference == Item::TYPE_CHROMALUXE) {
				$pc = new RenfortPriceCalculator(['item'=>$renfort_item]);
				$detail->price_renfort = $pc->price($dl->work_width, $dl->work_height);
				echo 'Prix:'.$detail->price_renfort;
				$total_price += $detail->price_renfort;
			}
		}
		
		if($total_price != 0) {
			$dl->unit_price = $total_price;
			$dl->updatePrice();
			if(!$dl->save()) {
				Yii::trace(print_r($dl->errors, true), 'WebsiteOrderLine::createOrderLine');
				$ok = false;
			}
		}
		
		if(!$detail->save()) {
			Yii::trace(print_r($detail->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		return $ok;
	}

}
