<?php

namespace app\commands;

use app\models\WebsiteOrder;

use yii\console\Controller;
use Yii;

class WebsiteController extends Controller {
	protected $url = 'http://labojjmicheli.be/bin/';
	protected $newOrders = false;
	
	const DIRECTORY_EMPTY = 'none';
	const NO_SUCH_FILE = 'no_such_file';
	
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
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
    protected function check_date($date) {
		Yii::trace('Checking '.$date, 'WebsiteController::check_date');
		$base_url = YII_ENV == 'dev' ? 'http://mac-de-pierre.local:8080/print/test/' : $this->url;
		$list_url = $base_url . 'list?d=' . $date;
		$filenames_str = $this->get_data($list_url);
		if($filenames_str != self::DIRECTORY_EMPTY) {
			$filenames = explode(';', $filenames_str);
			foreach($filenames as $filename) {
				if(! WebsiteOrder::findOne(['order_name' => $filename])) {					
					$file_url = $base_url . 'get?f=' . $filename;
					$json     = $this->get_data($file_url);
					if($json != self::NO_SUCH_FILE) {
						$wso = new WebsiteOrder([
							'order_name' => $filename,
							'rawjson' => $json,
							'status' => WebsiteOrder::STATUS_CREATED
						]);
						if($wso->save())
							$this->newOrders = true;
					}
				}
			}
		}
	}

    public function actionFetchOrders($date) {
		for($i = 7; $i > 0; $i--) {
			$day = date('Y-m-d', strtotime('now - '.$i.' days'));
			$this->check_date($day);
		}
		if($this->newOrders)
			$this->makeOrders();
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
	
	public function actionParseJson() {
		foreach(WebsiteOrder::find()->andWhere(['status' => WebsiteOrder::STATUS_CREATED])->each() as $wso) {
			$wso->parse_json();
		}
	}

	public function actionMakeOrders() {
		$this->makeOrders();
    }

}