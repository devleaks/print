<?php

namespace app\models;

use app\components\EuVATValidator;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "website_order".
 */
class WebsiteOrder extends _WebsiteOrder
{
	/** json uploaded, not parsed */
	const STATUS_CREATED = 'CREATED';
	/** json parsed */
	const STATUS_OPEN = 'OPEN';
	/** Order created from parsed json */
	const STATUS_CLOSED = 'CLOSED';

	const STATUS_WARN = 'WARN';
	const STATUS_CANCELLED = 'CANCEL';
	
	
	const TYPE_CERA = 'CERA';
	const TYPE_NORMAL = 'NORMAL';
	const SHIP = 'ship';

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

	public static function getStatuses() {
		return [
			self::STATUS_CREATED => Yii::t('store', self::STATUS_CREATED),
			self::STATUS_OPEN => Yii::t('store', self::STATUS_OPEN),
			self::STATUS_WARN => Yii::t('store', self::STATUS_WARN),
			self::STATUS_CLOSED => Yii::t('store', self::STATUS_CLOSED),
			self::STATUS_CANCELLED => Yii::t('store', self::STATUS_CANCELLED),
		];
	}

	/**
	 * Generates colored labels for Document. Color depends on document status.
	 *
	 * @return string HTML fragment
	 */
	public function getStatusLabel() {
		$colors = [
			self::STATUS_CANCELLED => 'warning',
			self::STATUS_CLOSED => 'success',
			self::STATUS_CREATED => 'success',
			self::STATUS_OPEN => 'primary',
			self::STATUS_WARN => 'warning',
		];
		return '<span class="label label-'.$colors[$this->status].'">'.Yii::t('store', $this->status).'</span>';
	}

	public function parse_json() {
		if($this->status != self::STATUS_CREATED)
			return;

		if(!$weborder = json_decode($this->rawjson)) {
			$this->errors[] = 'Cannot decode JSON.';
			return false;
		}		

		$transaction = Yii::$app->db->beginTransaction();
		
		if(in_array(strtoupper($weborder->type), [self::TYPE_CERA, self::TYPE_NORMAL])) {
			$this->order_type = strtoupper($weborder->type);
		} else {
			$this->errors[] = 'Wrong order type "'.$weborder->type.'".';
		}
		$this->order_date = $weborder->date;
		$this->order_id = $weborder->order_id;
		$this->name = $weborder->name;
		$this->company = $weborder->company;
		$this->address = $weborder->address;
		$this->postcode = $weborder->postcode;
		$this->city = $weborder->city;
		$this->vat = $weborder->vat;
		$this->phone = $weborder->phone;
		$this->email = $weborder->email;
		$this->clientcode = $weborder->client;
		$this->promocode = $weborder->promocode;
		$this->delivery = strtoupper($weborder->delivery);
		$this->comment = $weborder->comments;

		$lines_ok = true;
				
		foreach($weborder->products as $product) {
			$wol = new WebsiteOrderLine([
				'website_order_id' => $this->id,
				'filename' => $product->filename,
				'finish' => $product->finish,
				'profile' => $product->profile,
				'quantity' => $product->quantity,
				'format' => $product->format,
				'width' => $product->width,
				'height' => $product->height,
				'comment' => $product->comments,
			]);
			if($lines_ok) {
				$lines_ok = $wol->save();
				Yii::trace(print_r($wol->errors, true), 'WebsiteOrder::parse_json');
			}
		}
		
		$this->status = WebsiteOrder::STATUS_OPEN;
		if($lines_ok && $this->save())
			$transaction->commit();
		else {
			Yii::trace(print_r($this->errors, true), 'WebsiteOrder::parse_json');
			$transaction->rollback();
		}
	}

	public function isPromo() {
		return in_array(strtolower($this->promocode), ['cera', '1cera15', '1cera2015']);
	}
	
	public function isFormatOk($format) {
		return in_array($format, ['40x60','50x50','60x90','80x120','100x100','50x100']);
	}

	protected function findIfOnlyOne($conditions) {
		if(Client::find()->andWhere($conditions)->count() == 1) {
			Yii::trace('Client match on '.print_r($conditions, true), 'WebsiteOrder::findIfOnlyOne');
			return Client::find()->andWhere($conditions)->one();
		}
		Yii::trace('Client **NO** match on '.print_r($conditions, true), 'WebsiteOrder::findIfOnlyOne');
		return null;
	}

	protected function findClient() {
		$clean_vat = EuVATValidator::cleanVAT($this->vat);
		
		if($clean_vat) {			
			if($client = $this->findIfOnlyOne(['numero_tva' => $clean_vat]))
				return $client;
		}

		if($client = $this->findIfOnlyOne(['upper(reference_interne)' => strtoupper($this->clientcode)]))
			return $client;
		
		if($client = $this->findIfOnlyOne(['upper(autre_nom)' => strtoupper($this->company)]))
			return $client;
		
		$name_parts = explode(' ', $this->name);
		foreach($name_parts as $str) {
			if(strlen($str) > 3) {
				if($client = $this->findIfOnlyOne(['upper(nom)' => strtoupper($str)]))
					return $client;
			}
		}
			
		return null;
	}
	
	protected function createClient() {
		$client = new Client([
			'nom' => $this->name,
			'autre_nom' => $this->company,
			'numero_tva' => EuVATValidator::cleanVAT($this->vat),
			'adresse' => $this->address,
			'localite' => $this->city,
			'email' => $this->email,
			'reference_interne' => $this->clientcode,
			'comptabilite' => Client::getUniqueIdentifier($this->name),
			'gsm' => $this->phone,
		]);
		$client->save();
		Yii::trace(print_r($client->errors, true), 'WebsiteOrder::createClient');
		return $client;
	}
	
	protected function getClient() {
		$client = null;
		if(! $client = $this->findClient()) {
			$client = $this->createClient();
		}
		return $client;
	}
	
	protected function getShippingItem($dimensions) {
		$highest = null;
		if( $this->order_type == WebsiteOrder::TYPE_CERA
		 && $this->isPromo()
			) {
			foreach($dimensions as $key => $count) {
				$item_ref = WebsiteOrderLine::PROMOCODE.$key.WebsiteOrderLine::PROMOCODE_SH;
				if($shipping = Item::findOne(['reference' => $item_ref])) {
					if($highest == null) {
						$highest = $shipping;
					} else {
						if($highest->prix_de_vente < $shipping->prix_de_vente) {
							$highest = $shipping;
						}
					}
				}
			}
		} else {
			$largest = null;
			foreach($dimensions as $dim => $count) {
				// Yii::trace('NORMAL: testing='.$dim, 'WebsiteOrder::getShippingItem');
				if(!$largest) {
					$largest = explode('x', $dim);
				} else {
					$d = explode('x', $dim);
					if($d[0] > $largest[0]) {
						$largest[0] = $d[0];
					}
					if($d[1] > $largest[1]) {
						$largest[1] = $d[1];
					}
				}
			}
			// $largest contains largest dimensions
			Yii::trace('NORMAL: largest='.print_r($largest, true), 'WebsiteOrder::getShippingItem');
			
			// Dimensions have to be sorted by smallest width first and smallest height first.
			$standard_dimensions = ['20x30','30x30','30x45','40x40','40x50','40x60','50x50','50x60','50x75','60x60','60x90','70x70','75x75','70x100'];
			$fit = null;
			foreach($standard_dimensions as $dim) {
				$d = explode('x', $dim);
				if(($d[0] >= $largest[0]) && (($d[1] >= $largest[1]))) {
					Yii::trace('NORMAL: fit='.$dim, 'WebsiteOrder::getShippingItem');
					$item_ref = WebsiteOrderLine::SHIPCODE.$dim;
					if(!$highest) {
						$highest = Item::findOne(['reference' => $item_ref]);
					} else {
						$new_ship = Item::findOne(['reference' => $item_ref]);
						if($new_ship->prix_de_vente < $highest->prix_de_vente) {
							$highest = $new_ship;
							Yii::trace('NORMAL: '.$dim.' cheaper', 'WebsiteOrder::getShippingItem');
						} else {
							Yii::trace('NORMAL: '.$dim.' not cheaper', 'WebsiteOrder::getShippingItem');
						}
					}
				}
			}
		}
		return $highest;
	}
	
	protected function getComment() {
		$str = $this->promocode ? 'Promo '.$this->promocode.'. ' : '';
		$str .= $this->clientcode ? 'NVB Kl. '.$this->clientcode.'. ' : '';
		$str .= $this->comment;
		return substr($str, 0, 160);
	}

	public function createOrder() {
		$delay = Parameter::getIntegerValue('website', 'order_delay', 10);
		
		if($this->status != self::STATUS_OPEN)
			return;

		$transaction = Yii::$app->db->beginTransaction();
		$ok = true;

		// 1. ORDER
		$client = $this->getClient();
		Yii::trace('Client id='.$client->id, 'WebsiteOrder::createOrder');
		$sale = Sequence::nextval('sale');
		$order = new Document([
			'document_type' => Document::TYPE_ORDER,
			'client_id' => $client->id,
			'due_date' => date('Y-m-d', strtotime('now + '.$delay.' days')),
			'sale' => $sale,
			'reference' => Document::commStruct(date('y')*10000000 +$sale),
			'reference_client' => $this->order_id,
			'note' => $this->getComment(),
			'name' => substr($this->created_at,0,4).'-W-'.Sequence::nextval('doc_number'),
			'status' => Document::STATUS_CREATED,
		]);
		$ok = $order->save();
		$order->refresh();
		echo 'Check 1:'.($ok?'Y':'N').'
';
		Yii::trace(print_r($order->errors, true), 'WebsiteOrder::createOrder');

		// 2. ORDER LINES
		$dimensions = [];
		foreach($this->getWebsiteOrderLines()->each() as $wol) {
			if($ok) {
				$dim = $wol->getFormat();
				$dimensions[$dim] = isset($dimensions[$dim]) ? $dimensions[$dim] + 1 : 1;
				$ok = $wol->createOrderLine($order, $this);
				if(!$ok) {
					$this->addError('id', 'Problem building order line '.$wol->id);
				}
			}
		}
		echo 'Check 2:'.($ok?'Y':'N').'
';
		
		
		// 3. SHIPPING (if any)
		$shipping = null;
		if($this->delivery) {
			if($shipping = $this->getShippingItem($dimensions)) {
				$dl = new DocumentLine([
					'document_id' => $order->id,
					'item_id' => $shipping->id,
					'quantity' => 1,
					'unit_price' => $shipping->prix_de_vente,
					'vat' => $shipping->taux_de_tva,
					'due_date' => $order->due_date,
				]);
				$dl->updatePrice();
				if(!$dl->save()) {
					Yii::trace(print_r($dl->errors, true), 'WebsiteOrder::createOrder');
					$ok = false;
				}				
			}
		}

		echo 'Check 3:'.($ok?'Y':'N').'
';

		echo 'OT:'.$this->order_type;

		$order->updatePrice();
		
		// if shipping required but could not estimate price, put in status WARN
		$order->status = ($this->delivery && !$shipping) ? Document::STATUS_WARN : Document::STATUS_OPEN;

		// 4. This WEB ORDER REQUEST is processed, we have an ORDER in the system
		$this->status = self::STATUS_CLOSED;

		if($ok && $order->save() && $this->save()) {
			$this->document_id = $order->id;
			$this->save();
			$transaction->commit();
		} else {
			$transaction->rollback();
			$order = null;
			// we try this:
			echo 'Errors: '.print_r($this->errors, true);
			$this->convert_errors = print_r($this->errors, true);
			if(!$this->save()) {
				echo 'Errors2: '.print_r($this->errors, true);
			}
		}
		
		return $order;
	}

}
