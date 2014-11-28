/**
 *	Helper functions
 */
function arrondir(i) {
	return Math.round( i );
}

function arrondir2(i) {
	return Math.round( i * 100 ) / 100;
}

function arrondir_sup(i) {
	return Math.ceil( i );
}

function getItemDescription(id) {
	console.log('fetching '+id);
	item = null;
	$.ajax({
		type: "GET",
		url: store_values.ajaxUrl,
		dataType: 'json',
		async: !1,
		data: {
			id: id
		},
		success: function(data) {
			item = data.item;
		},
	});
	return item;
}

function getVal(s) {
	ret = parseFloat( $(s).val() );
	return isNaN(ret) ? 0 : ret;
}


/**
 *	Changes in main order line
 *  Compute prices with quantity and VAT, triggers compute rebate/supplement
 */
$("#orderline-quantity, #orderline-unit_price, #orderline-vat").change( function() {
	$("#orderline-price_htva").val(arrondir2( $("#orderline-quantity").val()   * $("#orderline-unit_price").val() ));
	$("#orderline-price_tvac").val(arrondir2( $("#orderline-price_htva").val() * (1 + $("#orderline-vat").val() / 100) ));
	$("#orderline-extra_amount").trigger("change");
});


/**
 *	Changes in main order line
 *  Compute rebate/supplement
 */
$("#orderline-extra_amount, #orderline-extra_type").change( function() {
	extra_type = $("#orderline-extra_type").val();
	if(extra_type != '') {
		amount = parseFloat(extra_type.indexOf("PERCENT") > -1 ? $("#orderline-price_htva").val() * ($("#orderline-extra_amount").val()/100) : $("#orderline-extra_amount").val());
		if(amount > 0) {
			asigne = extra_type.indexOf("SUPPLEMENT_") > -1 ? 1 : -1;
			amount = arrondir2(asigne * amount);
			$("#orderline-extra_htva").val(amount);
			$("#orderline-final_htva").val(arrondir2(parseFloat($("#orderline-price_htva").val()) + amount));
			$("#orderline-final_tvac").val(arrondir2(parseFloat($("#orderline-final_htva").val()) * (1 + $("#orderline-vat").val() / 100)));
		} else if (amount == 0) {
			$("#orderline-extra_htva").val('');
			$("#orderline-final_htva").val('');
			$("#orderline-final_tvac").val('');
		}
	} else {
		$("#orderline-extra_htva").val('');
		$("#orderline-final_htva").val('');
		$("#orderline-final_tvac").val('');
	}
});



/**
 *	ChromaLuxe and Fine Art special item price calculation
 */
function arrayHasOwnIndex(array, prop) {
    return array.hasOwnProperty(prop) && /^0$|^[1-9]\d*$/.test(prop) && prop <= 4294967294; // 2^32 - 2
}

function getItem(id) {
	for (key in items)
		if(parseInt(items[key].id) == id) {
			console.log(id+'='+key);
	       	return items[key];	
		}
}

function getReference(id) {
	item = getItem(id);
	return item ? item.reference : null;
}



/** 
 *	Computations for Chromaluxe
 */
function chromaluxe_price(w, h) {
	which = $('input[name="OrderLineDetail[chroma_type]"]:checked').val();
	max_area = params['SublimationMaxHeight'].value_int * params['SublimationMaxWidth'].value_int;
	work_area   = width * height;
	if(work_area <= params['ChromaLuxeXS'].value_number) {
		price = items["ChromaXS"].price * work_area / max_area;
	} else if(work_area <= params['ChromaLuxeS'].value_number) {
		price = items["ChromaS"].price * work_area / max_area;
	} else if(work_area <= params['ChromaLuxeM'].value_number) {
		price = items["ChromaM"].price * work_area / max_area;
	} else if(work_area <= params['ChromaLuxeL'].value_number) {
		price = items["ChromaL"].price * work_area / max_area;
	} else if(work_area <= max_area) {
		price = items["ChromaXL"].price * work_area / max_area;
	} else {
		store_values.error_str = "Surface supérieure à 18700 cm2.";
	}
	price = arrondir_sup(price);
	$("#orderlinedetail-price_chroma:enabled").val(price);
	return price;
}

/** renfort */
function chromaluxe_renfort_price(w, h) {
	frame_id = parseInt($("#orderlinedetail-frame_id:enabled").val());
	console.log('renfort: frame: '+frame_id);
	price = 0;
	if(frame_id > 0) {
		if(w > 50 || h > 70) { // force renfort, but it is free
			$("#orderlinedetail-renfort_bool:enabled").prop('checked', 'checked');
			$("#orderlinedetail-renfort_bool").prop('disabled', true); //PROBLEM WITH MULTIPLE RENFORT & DISABLED: Must add class for item.
			$("#orderlinedetail-price_renfort:enabled").val(0);
		}
	} else {
		$("#orderlinedetail-renfort_bool").prop('disabled', false);
		renfort = $("#orderlinedetail-renfort_bool:enabled").is(':checked');
		console.log('Refort?'+renfort);
		if(renfort) {
			console.log('Renfort price: '+items['Renfort'].price);
			price = 2 * (w + h - 40) * items['Renfort'].price / 100;
			if(price < items['RenfortPrixMin'].price) price = items['RenfortPrixMin'].price;
			price = arrondir_sup(price);
			$("#orderlinedetail-price_renfort:enabled").val(price);
		} else {
			$("#orderlinedetail-price_renfort:enabled").val('');
		}
	}
	return price;
}

/** generic frame price based on perimeter and price per length */
function chromaluxe_frame_price(w, h, frame) {
	perimeter = (w + h) / 50; // 2 * (w + h) / 100 in meters
	console.log('perimeter: '+perimeter+', price:'+perimeter * frame.price);
	return arrondir(perimeter * frame.price);
}

/** Nielsen */
function nielsen_montage_price(w, h) {
	return (w + h) > 170 ? items['Montage170L'].price : items['Montage170S'].price;
}

/** Exhibit */
function exhibit_frame_price(w, h, frame) {
	// frame (len in cm, price in €/m)
	len = w + h;
	price = frame.price * len / 50;
	// adjustment
	base = (frame.reference == "Exhibite-X25Standard") ? items['MontageExhibiteBase2'].price : items['MontageExhibiteBase5'].price; // 
	if(w < 30 || h < 30) {
		price += base;
	} else if(len < 121) {
		price += base + (h-30 + w-30) * items['MontageExhibiteS'].price;		
	} else if (len < 130) {
		price += base + (h-30) * items['MontageExhibiteMH'].price + (w-20) * items['MontageExhibiteML'].price;		
	} else {
		price += base + (h-30 + w-30) * items['MontageExhibiteL'].price;		
	}
	return arrondir(price);
}

function exhibit_montage_price(w, h) {
	return (w + h) > 170 ? items['Montage170L'].price : items['Montage170S'].price;
}

/** frame and montage */
function chromaluxe_frame_montage_price(w, h) {
	price_frame = 0;
	frame_id = parseInt($("#orderlinedetail-frame_id:enabled").val());
	console.log('frame: '+frame_id);
	if(frame_id > 0) {
		frame = getItem(frame_id);
		if (typeof(frame) !== 'undefined') {
			price_montage = 0;
			console.log("frame_ref: "+frame.manufacturer+', '+frame.price);
			switch(frame.manufacturer) {
				case 'Nielsen':
					console.log('cadre nielsen');
					price_frame = chromaluxe_frame_price(w, h, frame);
					price_montage = nielsen_montage_price(w, h);
					break;
				case 'Exhibit':
					price_frame = exhibit_frame_price(w, h, frame);
					price_montage = exhibit_montage_price(w, h);
					console.log('cadre Exhibite');
					break;
				case 'Renfort': // renfort
					console.log('cadre renfort');
					price_frame = chromaluxe_renfort_price(w, h);
					price_montage = 0; // included in above
					break;
				default:
					break;
			}
			//console.log('frame / refort price: '+price);
			$("#orderlinedetail-price_frame:enabled").val(price_frame);
			montage = $("#orderlinedetail-montage_bool:enabled").is(':checked');
			console.log('montage:'+montage);
			if(montage) {
				$("#orderlinedetail-price_montage:enabled").val(price_montage);
				price_frame += price_montage;
			} else
				$("#orderlinedetail-price_montage:enabled").val('');

		}
	} else {
		$("#orderlinedetail-price_frame:enabled").val('');
		$("#orderlinedetail-price_montage:enabled").val('');
	}
	return price_frame;
}


/** taches */
function chromaluxe_taches_price(w, h) {
	return 0;
}

/** MAIN */
function chroma_price() {
	width = parseInt($("#orderline-work_width").val());
	console.log('w '+width);
	if(width > 0) {
		height = parseInt($("#orderline-work_height").val());
		console.log('h '+height);
		if(height > 0) { // we have both
			// convention: width is smallest dimension
			if(width > height) { // switch them
				whtmp = width;   width = height;  height = whtmp;
			} // now width <= height
			if(width > params['SublimationMaxWidth'].value_int || height > params['SublimationMaxHeight'].value_int) {
				store_values.error_str = "Largeur ou hauteur trop grande.";
			} else {
				which = $('input[name="OrderLineDetail[chroma_id]"]:checked').val();
				console.log('chromaluxe type:'+which);
				if(typeof(which) !== 'undefined') {
					// we have all data we need, let's compute the item ChromaLuxe price

					$("#store-missing-data").toggle(false);
					store_values.error_str = false;

					item_price = chromaluxe_price(width, height);
					console.log('e:'+store_values.error_str);
					if(store_values.error_str) return;

					item_price += chromaluxe_frame_montage_price(width, height);
					console.log('e:'+store_values.error_str);
					if(store_values.error_str) return;

					item_price += chromaluxe_renfort_price(width, height);
					console.log('e:'+store_values.error_str);
					if(store_values.error_str) return;

					item_price += chromaluxe_taches_price(width, height);
					console.log('e:'+store_values.error_str);
					if(store_values.error_str) return;

					item_price = arrondir2(item_price);
					$("#orderline-unit_price").val(item_price);
					$("#orderline-unit_price").trigger('change');
					return;
				} else {
					store_values.error_str = "Vous devez préciser le type de ChromaLuxe.";
				}
			}
		} else {
			store_values.error_str = "Vous devez entrer les mesures de l'article.";
		}
	} else {
		console.log('no width');
		store_values.error_str = "Vous devez entrer les mesures de l'article.";
	}
	console.log('error:'+store_values.error_str);
	$("#store-missing-data").html(store_values.error_str);
	$("#store-missing-data").toggle(true);
}



/**
 *	Computations for Fine Art
 */
function fineart_price() {
	$("#orderline-unit_price").val(
		  getVal('#orderlinedetail-price_tirage:enabled')
		+ getVal('#orderlinedetail-price_support:enabled')
		+ getVal('#orderlinedetail-price_protection:enabled')
		+ getVal('#orderlinedetail-price_collage:enabled')
	);
	$("#orderline-unit_price").trigger('change');
}

function compute_price() {
	item_id = $("#orderline-item_id").val();
	//console.log('item_id='+item_id);
	switch(parseInt(item_id)) {
		case store_values.item_id.chroma:
			chroma_price();
			break;
		case store_values.item_id.fineart:
			fineart_price();
			break;
	} // other items: no nothing
}

$("#methodForm").submit(function(e){
    e.preventDefault();
    var form = this;
    checkIndex('upload/segments.gen').done(function() {
        form.submit(); // submit bypassing the jQuery bound event
    }).fail(function () {
        alert("No index present!");
    });
});

/**
 *	jQuery hooks: If element changes, recompute order line prices
 */
$(".compute-price, #orderline-work_width, #orderline-work_height, input[name='OrderLineDetail[chroma_id]']").change(function() {
	compute_price();
});

$("#orderlinedetail-tirage_id:enabled").change(function() {
	item_id = $(this).val();
	if(isNaN(item_id)) { // none selected
		$('#orderlinedetail-price_tirage:enabled').val('');
		$('#orderlinedetail-price_support:enabled').val('');
		$('#orderlinedetail-price_protection:enabled').val('');
		$('#orderlinedetail-price_collage:enabled').val('');	
	}
	item = getItemDescription(item_id);
	$('#orderlinedetail-price_tirage:enabled').val(item.prix_de_vente);
	// enable or disable options depending on paper type
	paper_type = item.fournisseur;
	console.log(paper_type);
	// hide all
	$('div.field-orderlinedetail-finish_id').toggle(false);
	$('div.field-orderlinedetail-note').toggle(false);
	$('div.field-orderlinedetail-support_id').toggle(false);
	$('div.field-orderlinedetail-price_support').toggle(false);
	$('div.field-orderlinedetail-protection_id').toggle(false);
	$('div.field-orderlinedetail-price_protection').toggle(false);
	$('div.field-orderlinedetail-collage_id').toggle(false);
	$('div.field-orderlinedetail-price_collage').toggle(false);
	// show what is necessary and reset others option values
	switch(paper_type) {
		case 'Papier Photo':
			$('div.field-orderlinedetail-finish_id').toggle(true);
			$('#orderlinedetail-price_support:enabled').val('');
			$('#orderlinedetail-price_protection:enabled').val('');
			$('#orderlinedetail-price_collage:enabled').val('');	
			break;
		case 'Papier Fine Art':
			$('div.field-orderlinedetail-note').toggle(true);
			$('div.field-orderlinedetail-protection_id').toggle(true);
			$('div.field-orderlinedetail-price_protection').toggle(true);
			$('div.field-orderlinedetail-finish_id input[type="radio"]').prop('checked',false);
			$('#orderlinedetail-price_support:enabled').val('');
			$('#orderlinedetail-price_collage:enabled').val('');
			$("#orderlinedetail-protection_id:enabled").trigger('change');
			break;
		case 'Canvas':
			$('div.field-orderlinedetail-support_id').toggle(true);
			$('div.field-orderlinedetail-price_support').toggle(true);
			$('div.field-orderlinedetail-finish_id input[type="radio"]').prop('checked',false);
			$('#orderlinedetail-price_protection:enabled').val('');
			$('#orderlinedetail-price_collage:enabled').val('');	
			$("#orderlinedetail-support_id:enabled").trigger('change');
			break;
	}
	// adjust displayed prices
	compute_price();	
});

$("#orderlinedetail-support_id:enabled").change(function() {
	item_id = parseInt($(this).val());
	if(isNaN(item_id)) { // none selected
		$('#orderlinedetail-price_support:enabled').val('');
	} else {
		item = getItemDescription(item_id);
		$('#orderlinedetail-price_support:enabled').val(item.prix_de_vente);
	}
	compute_price();	
});

$("#orderlinedetail-protection_id:enabled").change(function() {
	item_id = parseInt($(this).val());
	if(isNaN(item_id)) { // none selected
		$('#orderlinedetail-price_protection:enabled').val('');
	} else {
		item = getItemDescription(item_id);
		$('#orderlinedetail-price_protection:enabled').val(item.prix_de_vente);
	}
	compute_price();	
});

$("#orderlinedetail-collage_id:enabled").change(function() {
	item_id = parseInt($(this).val());
	if(isNaN(item_id)) { // none selected
		$('#orderlinedetail-price_collage:enabled').val('');
	} else {
		item = getItemDescription(item_id);
		$('#orderlinedetail-price_collage:enabled').val(item.prix_de_vente);
	}
	compute_price();	
});



/**
 *	Item with free name, price, and VAT
 */
$("#orderlinedetail-free_item_price_htva:enabled, #orderlinedetail-free_item_vat:enabled").change(function() {
	$("#orderline-unit_price").val($("#orderlinedetail-free_item_price_htva:enabled").val().replace(",","."));
	$("#orderline-vat").val($("#orderlinedetail-free_item_vat:enabled").val().replace(",","."));
	$("#orderline-unit_price").trigger('change');
});

$('#orderlinedetail-submit').submit(function(e) {
	console.log('intercepted: '+$("#store-missing-data").is(":visible"))
	e.preventDefault();
	if(! $("#store-missing-data").is(":visible") )
        form.submit(); // submit bypassing the jQuery bound event
});

function free_item_update() {
	$("#orderline-unit_price").prop('readonly', false);
	$("#orderline-vat").prop('readonly', false);
}

/**
 *	Enable/disable fields depending on item.
 *  Important note: Disabled form fields are not submited (HTML rule)
 *  Important note 2: This form contains an hardcoded form name
 */
$(".order-option").click(function () {
	item_id = $(this).data('item_id');
	console.log('class prefix='+store_values.class_prefix)
	class_prefix = 'input.item';
	console.log('class prefix='+class_prefix)
	
	$(class_prefix+store_values.item_id.chroma).prop('disabled', true);
	$(class_prefix+store_values.item_id.fineart).prop('disabled', true);
	$(class_prefix+store_values.item_id.freeitem).prop('disabled', true);
	$(class_prefix+item_id).prop('disabled', false);

	$(class_prefix+store_values.item_id.chroma).find('input').prop('disabled', true);
	$(class_prefix+store_values.item_id.fineart).find('input').prop('disabled', true);
	$(class_prefix+store_values.item_id.freeitem).find('input').prop('disabled', true);
	$(class_prefix+item_id).find('input').prop('disabled', false);

	item_name = $(this).data('item_name');
	item_vat = $(this).data('item_vat');
	$("#orderline-item_id").val(item_id);
	$("#orderline-vat").val(item_vat);
	console.log('item id:'+item_id);
	if(item_id == store_values.item_id.fineart) {
		$('div.field-orderlinedetail-finish_id').toggle(false);
		$('div.field-orderlinedetail-note').toggle(false);
		$('div.field-orderlinedetail-support_id').toggle(false);
		$('div.field-orderlinedetail-price_support').toggle(false);
		$('div.field-orderlinedetail-protection_id').toggle(false);
		$('div.field-orderlinedetail-price_protection').toggle(false);
		$('div.field-orderlinedetail-collage_id').toggle(false);
		$('div.field-orderlinedetail-price_collage').toggle(false);		
	}

	///ATTENTION SELECT_DISPLAY_ID HARDCODED HERE
	test = $('#select2-chosen-2');
	if($('#select2-chosen-2').length != 0)
		$('#select2-chosen-2').html(item_name);
	else
		$('#select2-chosen-1').html(item_name);

	console.log('item '+item_id+' set');
	compute_price();
});