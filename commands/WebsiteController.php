<?php

namespace app\commands;

use app\models\Document;
use app\models\WebsiteOrder;

use yii\console\Controller;
use Yii;

class WebsiteController extends Controller {
	protected $url = 'http://www.labojjmicheli.be/nl/plugins/';
	protected $newOrders = false;
	
	const DIRECTORY_EMPTY = 'none';
	const NO_SUCH_FILE = 'no_such_file';
	
	protected $dev = false;
	
	/**
	 *  Fetch website orders and save them if they do not exists.
	 *
	 */
	protected function get_data($url) {
		Yii::trace('Trying '.$url, 'WebsiteController::get_data');
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		// curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		$data_raw = curl_exec($ch);
		$data = mb_convert_encoding(curl_exec($ch), 'UTF-8', 'ISO-8859-1');
		// $data = utf8_decode(data_raw);
		// $data = mb_convert_encoding($data_raw, 'ISO-8859-1', 'auto');
		
		curl_close($ch);
		return $data;
	}
	
    protected function check_date($d = null) {
		$date = $d ? $d : date('d-m-Y');
		Yii::trace('Checking '.$date, 'WebsiteController::check_date');
		$base_url = $this->dev ? 'http://imac.local:8080/print/test/get-order' : $this->url.'get_order.php';
		$list_url = $base_url . '?date=' . $date;
		$filenames_str = $this->get_data($list_url);
		if($filenames_str != self::DIRECTORY_EMPTY) {
			$filenames = explode(',', $filenames_str);
			foreach($filenames as $filename) {
				if(! WebsiteOrder::findOne(['order_name' => $filename])) {					
					$file_url = $base_url . '?file=' . $filename;
					$json     = $this->get_data($file_url);
					if($json != self::NO_SUCH_FILE) {
						$wso = new WebsiteOrder([
							'order_name' => $filename,
							'rawjson' => $json,
							'status' => WebsiteOrder::STATUS_CREATED
						]);
						if($wso->save())
							$this->newOrders = true;
						else
							Yii::trace('WSO: '.print_r($wso->errors, true), 'WebsiteController::check_date');
					}
				}
			}
		}
	}

    public function actionFetchOrders($date) {
		for($i = ($this->dev ? 0 : 7); $i >= 0; $i--) {
			$day = date('d-m-Y', strtotime('now - '.$i.' days'));
			$this->check_date($day);
		}
		if($this->newOrders) {
			foreach(WebsiteOrder::find()->andWhere(['status' => WebsiteOrder::STATUS_CREATED])->each() as $wso) {
				$wso->parse_json();
			}
			$this->makeOrders();
		}
	}

	/**
	 *  Create Orders from website orders.
	 *
	 */
    protected function makeOrders() {
		foreach(WebsiteOrder::find()->andWhere(['status' => WebsiteOrder::STATUS_OPEN])->each() as $wso) {
			if($order = $wso->createOrder()) {
				Yii::trace('Order '.$order->id.' created from file '.$wso->order_name, 'WebsiteController::actionMakeOrders');
			} else {
				Yii::trace('ERROR Creating order for file '.$wso->order_name, 'WebsiteController::actionMakeOrders');
			}
		}
	}
	
	public function actionParseOrders() {
		foreach(WebsiteOrder::find()->andWhere(['status' => WebsiteOrder::STATUS_CREATED])->each() as $wso) {
			$wso->parse_json();
		}
	}

	public function actionMakeOrders() {
		$this->makeOrders();
    }

	public function actionReset() {
		$docs = [];
		foreach(WebsiteOrder::find()->each() as $wso) {
			if($wso->document_id)
				$docs[] = $wso->document_id;
			foreach($wso->getWebsiteOrderLines()->each() as $wsol)
				$wsol->delete();
			$wso->delete();
		}
		foreach(Document::find()->andWhere(['id' => $docs])->each() as $doc) {
			$doc->deleteCascade();
		}
	}

}