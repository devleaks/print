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

    public function actionGet($f = null)
    {
		$o = [
			'date' => "14-08-2015",					// dd-mm-yyyy
			'name' => "Fullname",
			'company' => "Labo JJ Micheli",
			'address' => "Street Adress and 34",
			'city' => "City and 1000",
			'vat' => "BE0428759497",
			'client' => "client number",
			'phone' => "+32-2-123 45 67",
			'email' => "labojjmicheli@gmail.com",
			'comments' => "Delivery method",		// length limit 160 chars
			'promocode' => "PromoCode",
			'products' => [							//product objects
				[
					'filename' => "mypic.jpg",		// limit on length 80 chars
					'format' => "40x60",			// [40x60|50x50]
					'quantity' => 1,				// 1 - 4 (max?)
					'finish' => "glossy",			// [glossy|mat]
					'profile' => "yes",				// [yes|no]
					'comments' => "Comments about this product"	// length limit 160 chars
				],
				[
					'filename' => "myotherpic.jpg",
					'format' => "50x50",
					'quantity' => 1,
					'finish' => "mat",
					'profile' => "no",
					'comments' => "Keep it square"
				]
			]
		];
		echo json_encode($o);
    }

}
