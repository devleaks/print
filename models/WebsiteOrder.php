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
	
	/** Order types */
	const TYPE_CERA = 'CERA';
	const TYPE_NORMAL = 'NORMAL';

	/** Shipment keyword */
	const SHIP = 'ship';
	
	const DELIVERY_PICKUP = 'PICKUP';
	const DELIVERY_SEND = 'SEND';
	
	public $warnings;

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

	public function init() {
		parent::init();
		$warnings = [];
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

/* Example of JSON file:

{
	"date":"14-10-2015",
	"order_type":"NORMAL",
	"order_id":"151000004",
	"name":"COENE",
	"company":"",
	"address":"Avenue des Alliés 16",
	"city":"Waterloo",
	"zipcode":"1410",
	"vat":"",
	"client":"",
	"language":"nl",
	"phone":"0476",
	"email":"contact@e-telier.be",
	"delivery":"PICKUP",
	"comments":"",
	"promocode":"",
	"products":[
		{
			"filename": "Test 1 - 35x46 - 3 - Zilver mat - Pro",
			"format": "35x46",
			"quantity": 3,
			"finish": "CLEARMAT",
			"profile": "RENFORTPRO",
			"comments": "Test 1 - 35x46 - 3 - Zilver mat - Pro"
		},
		{
			"filename": "Test 2 - 50x50 - 2 - Mat - Ja",
			"format": "50x50",
			"quantity": 2,
			"finish": "WHITEMAT",
			"profile": "RENFORT", "comments": "Test 2 - 50x50 - 2 - Mat - Ja"
		}
	]
}

*/

		$transaction = Yii::$app->db->beginTransaction();
		
		$clean_json = preg_replace("/[\n\r]/","",$this->rawjson);

		if(!$weborder = json_decode($clean_json)) {
			$this->warnings[] = 'Fatal error: Cannot decode JSON.';
			$this->convert_errors = print_r($this->warnings, true);
			$this->status = self::STATUS_WARN;
			if(!$this->save(false))
				Yii::trace(print_r($this->errors, true), 'WebsiteOrder::parse_json');
			$transaction->commit();
			return false;
		}		

		if(in_array(strtoupper($weborder->order_type), [self::TYPE_CERA, self::TYPE_NORMAL])) {
			$this->order_type = strtoupper($weborder->order_type);
		} else {
			$this->warnings[] = 'Wrong order type "'.$weborder->order_type.'".';
			$this->convert_errors = print_r($this->warnings, true);
			if(!$this->save(false))
				Yii::trace(print_r($this->errors, true), 'WebsiteOrder::parse_json');
			$transaction->commit();
			return false;
		}

		// $this->warnings[] = 'Test entry.';

		$delivery = null;
		if(in_array(strtoupper($weborder->delivery), [self::DELIVERY_PICKUP,self::DELIVERY_SEND])) {
			$delivery = strtoupper($weborder->delivery);
		} else {
			$this->warnings[] = 'Wrong order delivery "'.$weborder->delivery.'".';
		}

		$this->order_date = $weborder->date;
		$this->order_id = $weborder->order_id;
		$this->name = $weborder->name;
		$this->company = $weborder->company;
		$this->address = substr($weborder->address, 0, 160);
		$this->postcode = $weborder->zipcode;
		$this->city = $weborder->city;
		$this->vat = $weborder->vat;
		$this->phone = $weborder->phone;
		$this->email = $weborder->email;
		$this->clientcode = $weborder->client;
		$this->promocode = $weborder->promocode;
		$this->delivery = $delivery;
		$tmp = substr($weborder->comments, 0, 160);
		$this->comment = $tmp ? $tmp : '';
		
		if($this->order_type == self::TYPE_CERA && !$this->isPromo()) {
			$this->warnings[] = 'Order of type CERA but wrong promo code "'.$this->promocode.'".';
			$this->convert_errors = print_r($this->warnings, true);
			if(!$this->save(false))
				Yii::trace(print_r($this->errors, true), 'WebsiteOrder::parse_json');
			$transaction->commit();
			return false;
		}

		$ok = true;
				
		foreach($weborder->products as $product) {
			$profile = null;
			if(in_array(strtoupper($product->profile), ['JA','OUI','RENFORT','YES'])) {
				$profile = WebsiteOrderLine::RENFORT;
			} else if (in_array(strtoupper($product->profile), ['PRO','RENFORT_PRO','RENFORTPRO'])) {
				$profile = WebsiteOrderLine::RENFORT_PRO;
			}
			$finish = null;
			if(in_array(strtoupper($product->finish), ['WHITEGLOSSY','WHITEMAT','CLEARMAT'])) {
				$finish = strtoupper($product->finish);
			}
			$sizes = [];
			$ctl = preg_match('/[^\d]*(\d+)[^\d]+(\d+)/', $product->format, $sizes);
			$width = min($sizes[1], $sizes[2]);
			$height = max($sizes[1], $sizes[2]);
			// echo print_r($sizes, true);
			
			$tmp = substr($product->comments, 0, 160);

			$wol = new WebsiteOrderLine([
				'website_order_id' => $this->id,
				'filename' => $product->filename,
				'finish' => $finish,
				'profile' => $profile,
				'quantity' => $product->quantity,
				'format' => $product->format,
				'width' => $width,
				'height' => $height,
				'comment' => $tmp ? $tmp : '',
			]);
			if($ok) {
				$ok = $wol->save();
				Yii::trace(print_r($wol->errors, true), 'WebsiteOrder::parse_json');
			}
		}
		
		$this->status = WebsiteOrder::STATUS_OPEN;
		$this->convert_errors = print_r($this->warnings, true);
		if($ok && $this->save()) {
			$transaction->commit();
		} else {
			Yii::trace(print_r($this->warnings, true), 'WebsiteOrder::parse_json');
			$transaction->rollback();
		}
	}

	public function isNVBOk() {
		return $this->clientcode != '';
	}
	
	public function isPromo() {
		return $this->isNVBOk() && in_array(strtolower($this->promocode), ['cera', '1cera15', '1cera2015']);
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
			if($client = $this->findIfOnlyOne(['numero_tva_norm' => $clean_vat]))
				return $client;
		}

		if(strlen($this->email) > 5) {
			if($client = $this->findIfOnlyOne(['lower(email)' => strtolower($this->email)]))
				return $client;
		}
		
		if(strlen($this->clientcode) > 3) {
			if($client = $this->findIfOnlyOne(['lower(reference_interne)' => strtolower($this->clientcode)]))
				return $client;
		}
		
		if(strlen($this->company) > 3) {
			if($client = $this->findIfOnlyOne(['lower(autre_nom)' => strtolower($this->company)]))
				return $client;
		}
		
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
			'lang' => 'nl',
			'reference_interne' => $this->clientcode,
			'comptabilite' => Client::getUniqueIdentifier($this->name),
			'gsm' => $this->phone,
		]);
		$client->save();
		if(count($client->errors) > 0) Yii::trace(print_r($client->errors, true), 'WebsiteOrder::createClient');
		return $client;
	}
	
	protected function getClient() {
		$client = $this->findClient();
		if(! $client ) {
			$client = $this->createClient();
		} else { // check and update nvb
			if($client->reference_interne != $this->clientcode) {
				$client->reference_interne = $this->clientcode;
				$client->save();
			}
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
					//Yii::trace('NORMAL: fit='.$dim, 'WebsiteOrder::getShippingItem');
					$item_ref = WebsiteOrderLine::SHIPCODE.$dim;
					if(!$highest) {
						$highest = Item::findOne(['reference' => $item_ref]);
					} else {
						$new_ship = Item::findOne(['reference' => $item_ref]);
						if($new_ship->prix_de_vente < $highest->prix_de_vente) {
							$highest = $new_ship;
							//Yii::trace('NORMAL: '.$dim.' cheaper', 'WebsiteOrder::getShippingItem');
						} else {
							//Yii::trace('NORMAL: '.$dim.' not cheaper', 'WebsiteOrder::getShippingItem');
						}
					}
				}
			}
		}
		return $highest;
	}
	
	protected function getComment() {
		$str = '';
		if($this->isPromo() && $this->order_type == WebsiteOrder::TYPE_CERA) {
			$str = $this->promocode ? 'Promo '.$this->promocode.'. ' : '';
			$str .= $this->clientcode ? 'NVB Kl. '.$this->clientcode.'. ' : '';
		} else if($this->isNVBOk()) {
			$str = $this->promocode ? 'Promo NVB. ' : '';
			$str .= $this->clientcode ? 'Kl nr. '.$this->clientcode.'. ' : '';
		}
		$str .= $this->comment;
		return $str ? substr($str, 0, 160) :  '';
	}
	
	protected function setUser($model) {
		$admin = User::findOne(['username' => 'admin']);
		$model->created_by = $admin->id;
		$model->updated_by = $admin->id;
		$model->detachBehavior('userstamp'); // so that created_by/updated_by don't get updated
	}

	public function createOrder() {
		$delay = Parameter::getIntegerValue('website', 'order_delay', 10);
		
		if(Document::findOne($this->document_id))
			return;

		$transaction = Yii::$app->db->beginTransaction();
		$ok = true;

		// 1. ORDER
		$client = $this->getClient();
		Yii::trace('Client id='.$client->id, 'WebsiteOrder::createOrder');
		
		// force communication in NL?
		if($client->lang != 'nl') {
			$client->lang = 'nl';
			$client->save(false);
		}
		
		$sale = Sequence::nextval('sale');
		$order = new Document([
			'document_type' => Document::TYPE_ORDER,
			'client_id' => $client->id,
			'due_date' => date('Y-m-d', strtotime('now + '.$delay.' days')),
			'sale' => $sale,
			'reference' => Document::commStruct(date('y')*10000000 +$sale),
			'reference_client' => $this->order_id,
			'email' => $this->email,
			'note' => $this->getComment(),
			'name' => Document::generateName(Document::TYPE_WEB),
			'status' => Document::STATUS_CREATED,
		]);
		$this->setUser($order);
		$ok = $order->save();
		$order->refresh();
		if(count($order->errors) > 0) Yii::trace(print_r($order->errors, true), 'WebsiteOrder::createOrder');

		// 2. ORDER LINES
		$dimensions = []; // $dimensions['20x30'] contains the number of works of size 20x30. Width <= Height.
		foreach($this->getWebsiteOrderLines()->each() as $wol) {
			if($ok) {
				$dim = $wol->getFormat();
				$dimensions[$dim] = isset($dimensions[$dim]) ? $dimensions[$dim] + $wol->quantity : $wol->quantity;
				if($this->order_type == WebsiteOrder::TYPE_CERA && !$this->isFormatOk($dim)) {
					$this->warnings[] = 'Invalid format for CERA promo '.$dim;
				} else {
					$ok = $wol->createOrderLine($order, $this);
					if(!$ok) {
						$this->warnings[] = 'Problem building order line '.$wol->id;
					}
				}
			}
		}
		
		// 3. SHIPPING (if any)
		$shipping = null;
		if($this->delivery == self::DELIVERY_SEND) {
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

		$order->updatePrice();
		
		// if shipping required but could not estimate price, put in status WARN
		$order->status = (($this->delivery == self::DELIVERY_SEND) && !$shipping) ? Document::STATUS_WARN : Document::STATUS_OPEN;

		// 4. This WEB ORDER REQUEST is processed, we have an ORDER in the system
		if(count($this->warnings) > 0) {
			$this->convert_errors = print_r($this->warnings, true);
			$order->status = Document::STATUS_WARN;
		}
		$this->status = self::STATUS_CLOSED;

		if($ok && $order->save() && $this->save()) {
			$this->document_id = $order->id;
			$this->save();
			$transaction->commit();
		} else {
			$transaction->rollback();
			$order = null;
			// we try this:
			echo 'Errors: '.print_r($this->warnings, true);
			$this->convert_errors = print_r($this->warnings, true);
			if(!$this->save()) {
				echo 'createOrder Errors: '.print_r($this->errors, true);
			}
		}
		
		return $order;
	}

}
