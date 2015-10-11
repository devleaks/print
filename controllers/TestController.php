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
				'company' => "Labo JJ Micheli",
				'address' => "Street Adress and 34",
				'city' => "City",
				'postcode' => "1000",
				'vat' => "BE0428759497",
				'client' => "NVB123456789",
				'phone' => "+32-2-123 45 67",
				'email' => "labojjmicheli@gmail.com",
				'comments' => "Customer comment. In het vlaams.",		// length limit 160 chars
				'promocode' => "wrong",
				'delivery' => "ship",
				'products' => [							//product objects
					[
						'filename' => "mypic.jpg",		// limit on length 80 chars
						'format' => "40x60",			// [40x60|50x50]
						'width' => "40",
						'height' => "60",
						'quantity' => 4,				// 1 - 4 (max?)
						'finish' => "WHITEGLOSSY",			// [glossy|mat]
						'profile' => "yes",				// [yes|no]
						'comments' => "Comments about this product"	// length limit 160 chars
					],
					[
						'filename' => "myotherpic.jpg",
						'format' => "30x170",
						'width' => "30",
						'height' => "170",
						'quantity' => 2,
						'finish' => "WHITEMAT",
						'profile' => "no",
						'comments' => "Keep it square"
					]
				]
			]);
		}
    }

}
