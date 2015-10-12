<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "website_order_line".
 */
class WebsiteOrderLine extends _WebsiteOrderLine
{
	const SHIPCODE = 'SHIP-';
	const PROMOCODE = 'PROMOCERA15-';
	const PROMOCODE_SH = '-SH';
	
	const RENFORT = 'Renfort';
	const RENFORT_PRO = 'RenfortPro';
	

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


	public function getFormat() {
		return min($this->width, $this->height).'x'.max($this->width, $this->height);	
	}

	protected function getChromaType() {
		return Item::findOne(['reference' => 'Chroma'.$this->finish]);
	}
	
	protected function getProfileType() {
		return $this->profile ? Item::findOne(['reference' => $this->profile]) : null;
	}

	public function createOrderLine($order, $weborder) {
		$ok = true;
		$format = $this->getFormat();
		$unit_price = 0;
		$main_item = null;

		// 1. DOCUMENT LINE
		/** 1.1 Main Item */
		$rebate = false;
		
		if( $weborder->order_type == WebsiteOrder::TYPE_CERA
		 && $weborder->isFormatOk($format)
		 && $weborder->isPromo()
			) {
			$item_ref = self::PROMOCODE.$format;
			if($main_item = Item::findOne(['reference' => $item_ref])) {
				$unit_price += $main_item->prix_de_vente;
			}
		} else {
			if($main_item = Item::findOne(['reference' => Item::TYPE_CHROMALUXE])) {
				$pc = new ChromaLuxePriceCalculator();
				$price_chroma = $pc->price($this->width, $this->height);
				$unit_price += $price_chroma;
				$rebate = true;
			}
		}
		if(!$main_item) {
			Yii::trace('Could not find main item.', 'WebsiteOrderLine::createOrderLine');
			return false;
		}
		
		$dl = new DocumentLine([
			'document_id' => $order->id,
			'item_id' => $main_item->id,
			'quantity' => $this->quantity,
			'work_width' => $this->width,
			'work_height' => $this->height,
			'vat' => $main_item->taux_de_tva,
			'due_date' => $order->due_date,
		]);
		
		/** 1.2 Rebate */
		if($rebate && $weborder->isNVBOk()) {
			$dl->extra_type = DocumentLine::EXTRA_REBATE_PERCENTAGE;
			$dl->extra_amount = 10; // 10%		
		}
		
		$dl->updatePrice();
		if(!$dl->save()) {
			Yii::trace(print_r($dl->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		
		// 2. DOCUMENT LINE DETAIL
		$detail = new DocumentLineDetail();
		$detail->document_line_id = $dl->id;

		/** 2.1 ChromaLuxe details */
		if($finish_item = $this->getChromaType()) {
			$detail->chroma_id = $finish_item->id;
		} else {
			Yii::trace('could not find CL finish', 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}

		/** 2.2 Profile details */
		if($renfort_item = $this->getProfileType()) {
			$detail->renfort_id = $renfort_item->id;
			// Do we have to add the price?
			if($weborder->order_type == WebsiteOrder::TYPE_CERA && $this->profile == self::RENFORT) {
				$detail->price_renfort = 0;
			} else if($weborder->order_type == WebsiteOrder::TYPE_CERA && $this->profile == self::RENFORT_PRO) {
				$pc = new RenfortPriceCalculator(['item'=>$renfort_item]);
				$detail->price_renfort = $pc->price($dl->work_width, $dl->work_height);
				$unit_price += $detail->price_renfort;
			} else {
				$pc = new RenfortPriceCalculator(['item'=>$renfort_item]);
				$detail->price_renfort = $pc->price($dl->work_width, $dl->work_height);
				$unit_price += $detail->price_renfort;
			}			
		}
		if(!$detail->save()) {
			Yii::trace(print_r($detail->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}		

		/** 1.3 Final price, given order type and options */
		$dl->unit_price = $unit_price;
		$dl->updatePrice();
		if(!$dl->save()) {
			Yii::trace(print_r($dl->errors, true), 'WebsiteOrderLine::createOrderLine');
			$ok = false;
		}
		
		return $ok;
	}

}
