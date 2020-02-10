<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionList($d)
    {
		return 'AZERTY';
    }

    public function actionGetOrder($date = null, $file = null)
    {
		if($date != null) {
			echo 'dummy_filename';
		} else if ($file != null) {
			echo json_encode([
				'type' => "cera",
				'date' => "10-10-2015",					// dd-mm-yyyy
				'order_id' => "WEB-001",
				'name' => "Fullname",
				'company' => "Colorfields",
				'address' => "Street Adress and 34",
				'city' => "City",
				'zipcode' => "1000",
				'vat' => "BE0428759497",
				'client' => "NVB123456",
				'phone' => "+32-2-123 45 67",
				'email' => "info@colorfields.be",
				'comments' => "Customer comment. In het vlaams.",		// length limit 160 chars
				'promocode' => "cera",
				'delivery' => "send",
				'products' => [							//product objects
					[
						'filename' => "pic1.jpg",		// limit on length 80 chars
						'format' => "40x60",			// [40x60|50x50]
						'width' => "40",
						'height' => "60",
						'quantity' => 4,				// 1 - 4 (max?)
						'finish' => "WHITEGLOSSY",			// [glossy|mat]
						'profile' => "yes",				// [yes|no]
						'comments' => "Comments about this product"	// length limit 160 chars
					],
					[
						'filename' => "pic2.jpg",
						'format' => "30x110",
						'width' => "30",
						'height' => "110",
						'quantity' => 2,
						'finish' => "WHITEMAT",
						'profile' => "no",
						'comments' => "Keep it square"
					],
					[
						'filename' => "pic3.jpg",
						'format' => "50x50",
						'width' => "50",
						'height' => "50",
						'quantity' => 4,
						'finish' => "WHITEGLOSSY",
						'profile' => "RENFORTPRO",
						'comments' => "Comments about this product"
					],
				]
			]);
		}
    }

}
