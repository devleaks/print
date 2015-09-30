<?php

namespace app\models;

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

	protected function findClient() {
		if($client = Client::find()->filterWhere(['numero_tva' => $this->vat])->one())
			return $client;

		if($client = Client::find()->filterWhere(['reference_interne' => $this->clientcode])->one())
			return $client;
			
		return null;
	}
	
	protected function createClient() {
		$client = new Client([
			'nom' => $this->name,
			'autre_nom' => $this->company,
			'numero_tva' => $this->vat,
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
		$sale = Sequence::nextval('sale');
		$user = User::findOne(['username' => 'comptoir']);
		$order = new Document([
			'document_type' => Document::TYPE_ORDER,
			'client_id' => $client->id,
			'due_date' => date('Y-m-d', strtotime('now + 7 days')),
			'sale' => $sale,
			'reference' => Document::commStruct(date('y')*10000000 +$sale),
			'note' => $this->comment,
			'name' => substr($this->created_at,0,4).'-W-'.Sequence::nextval('doc_number'),
			'created_by' => $user->id,
			'updated_by' => $user->id,
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
			$transaction->commit();
		} else {
			$transaction->rollback();
		}
		
		return $order;
	}

}
