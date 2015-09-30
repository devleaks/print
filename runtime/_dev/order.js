var order = {
	date: "14-08-2015",					// dd-mm-yyyy
	name: "Fullname",
	company: "Labo JJ Micheli",
	address: "Street Adress and 34",
	city: "City and 1000",
	vat: "BE123456789",
	client: "client number",
	phone: "+32-2-123 45 67",
	email: "labojjmicheli@gmail.com",
	comments: "Delivery method",		// length limit 160 chars
	promocode: "PromoCode",
	products: [							//product objects
		{
			filename: "mypic.jpg",		// limit on length 80 chars
			format: "60x90",			// {40x60|50x50}
			quantity: 1,				// 1 - 4 (max?)
			finish: "glossy",			// {glossy|mat}
			profile: "yes",				// {yes|no}
			comments: "Comments about this product"	// length limit 160 chars
		},
		{
			filename: "myotherpic.jpg",
			format: "50x50",
			quantity: 1,
			finish: "mat",
			profile: "no",
			comments: "Keep it square"
		}
	]
	}
}

console.log(JSON.stringify(order));

$.ajax({
	type: "POST",
	url: "website-order",
	dataType: 'json',
	data: {
		order: order
	},
	success: function(data) {
		$('#feedback').text(data.result);
	},
	error: function(data) {
		console.log(data);
	},
});


public function actionWebsiteOrder($order) {
	echo Json::encode(['result' => Order::create($order)]);
}
