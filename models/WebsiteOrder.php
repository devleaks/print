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

		$weborder = json_decode($this->rawjson);

		$transaction = Yii::$app->db->beginTransaction();

		$this->order_date = $weborder->date;
		$this->name = $weborder->name;
		$this->company = $weborder->company;
		$this->address = $weborder->address;
		$this->city = $weborder->city;
		$this->vat = $weborder->vat;
		$this->phone = $weborder->phone;
		$this->email = $weborder->email;
		$this->clientcode = $weborder->client;
		$this->promocode = $weborder->promocode;
		$this->comment = $weborder->comments;

		$lines_ok = true;
				
		foreach($weborder->products as $product) {
			$wol = new WebsiteOrderLine([
				'website_order_id' => $this->id,
				'filename' => $product->filename,
				'finish' => $product->finish,
				'profile_bool' => in_array(strtolower($product->profile), ['yes','true','oui','ja']) ? 1 : 0,
				'quantity' => $product->quantity,
				'format' => $product->format,
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

	public function createOrder() {
		if($this->status != self::STATUS_OPEN)
			return;

		$transaction = Yii::$app->db->beginTransaction();

		$client = $this->getClient();
		echo 'Client id='.$client->id;
		$sale = Sequence::nextval('sale');
		//$user = User::findOne(['username' => 'comptoir']);
		$order = new Document([
			'document_type' => Document::TYPE_ORDER,
			'client_id' => $client->id,
			'due_date' => date('Y-m-d', strtotime('now + 7 days')),
			'sale' => $sale,
			'reference' => Document::commStruct(date('y')*10000000 +$sale),
			'note' => $this->comment,
			'name' => substr($this->created_at,0,4).'-W-'.Sequence::nextval('doc_number'),
			'status' => Document::STATUS_CREATED,
		]);
		$order->save();
		$order->refresh();
		Yii::trace(print_r($order->errors, true), 'WebsiteOrder::createOrder');
		
		$lines_ok = true;
		foreach($this->getWebsiteOrderLines()->each() as $wol) {
			if($lines_ok) {
				$lines_ok = $wol->createOrderLine($order);
			}
		}

		$order->updatePrice();
		$order->status = Document::STATUS_OPEN;

		$this->status = self::STATUS_CLOSED;

		if($order->save() && $this->save()) {
			$this->document_id = $order->id;
			$this->save();
			$transaction->commit();
		} else {
			$transaction->rollback();
		}
		
		return $order;
	}

}
