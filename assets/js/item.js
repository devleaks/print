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


/** Items */
function getItemById(id) {
	console.log('getItemById: fetching '+id);
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
		error: function(data) {
			console.log('getItemById: error: no '+id);
			add_error("ITEM_NOT_FOUND");
		},
	});
	return item;
}

function getItemByReference(ref) {
	console.log('getItemByReference: fetching '+ref);
	item = null;
	$.ajax({
		type: "GET",
		url: store_values.ajaxUrl+'-by-ref',
		dataType: 'json',
		async: !1,
		data: {
			ref: ref
		},
		success: function(data) {
			item = data.item;
		},
		error: function(data) {
			console.log('getItemByReference: error: no '+id);
			add_error("ITEM_NOT_FOUND");
		},
	});
	return item;
}

function getPrice(ref) {
	item = getItemByReference(ref);
	return item.prix_de_vente;
}

function getVal(s) {
	ret = parseFloat( $(s).val() );
	return isNaN(ret) ? 0 : ret;
}


function arrayHasOwnIndex(array, prop) {
    return array.hasOwnProperty(prop) && /^0$|^[1-9]\d*$/.test(prop) && prop <= 4294967294; // 2^32 - 2
}


/** Error registration, handling, and display */
function add_error(str) {
	if(store_values.errors.indexOf(str) == -1)
		store_values.errors.push(str);
	return show_errors();
}

function del_error(str) {
 	index = store_values.errors.indexOf(str);
	if(index > -1) {
		if(store_values.errors.length == 1)
			store_values.errors = new Array();
		else {
			newarr = Array();
			for(i=0;i<store_values.errors.length;i++) {
				if(store_values.errors[i] != str)
					newarr.push(store_values.errors[i]);
				$("#store-missing-data").html(errmsg);
			}
			store_values.errors = newarr;
			// DOES NOT WORK: store_values.errors = store_values.errors.splice(index, 1);
		}
	}
	return show_errors();
}

function clean_errors() {
	store_values.errors = new Array();
	$("#store-missing-data").html('');
	$("#store-missing-data").toggle(false);
}

function show_errors() {
	has_error = (store_values.errors.length > 0);
	if(has_error) {
		errmsg = '';
		for(i=0;i<store_values.errors.length;i++) {
			errmsg += ' '+store_values.error_msg[store_values.errors[i]];
			$("#store-missing-data").html(errmsg);
		}
	} else
		$("#store-missing-data").html('');
	$("#store-missing-data").toggle(has_error);
	return has_error;
}


/** Item dimensions. Raise error if not available */
function getDimensions() {
	needToFit = ($("#documentline-item_id").val() == store_values.item_id.chroma);
	width = parseInt($("#documentline-work_width").val());
//	console.log('w '+width);
	if(width > 0) {
		height = parseInt($("#documentline-work_height").val());
//		console.log('h '+height);
		if(height > 0) { // we have both
			del_error("NO_WORK_SIZE");
			// convention: width is smallest dimension
			if(width > height) { // switch them
				whtmp = width;   width = height;  height = whtmp;
			} // now width <= height

			del_error("WORK_TOO_LARGE");
			if(needToFit && (width > store_values.param['SublimationMaxWidth'].value_int || height > store_values.param['SublimationMaxHeight'].value_int)) {
				add_error("WORK_TOO_LARGE");
			} else {
				return {width: width, height: height};
			}

		} else {
			add_error("NO_WORK_SIZE");
		}
	} else {
		add_error("NO_WORK_SIZE");
	}
	return false;
}


/**
 *	Changes in main order line
 *  Compute prices with quantity and VAT, triggers compute rebate/supplement
 */
$("#documentline-quantity, #documentline-unit_price, #documentline-vat").change( function() {
	// change displays with _virgule
	$("#documentline-quantity_virgule").val($("#documentline-quantity").val().replace(".",","));
	$("#documentline-unit_price_virgule").val($("#documentline-unit_price").val().replace(".",","));
	$("#documentline-vat_virgule").val($("#documentline-vat").val().replace(".",","));
	
	$("#documentline-price_htva").val(arrondir2( $("#documentline-quantity").val()   * $("#documentline-unit_price").val() ));
	$("#documentline-price_tvac").val(arrondir2( $("#documentline-price_htva").val() * (1 + $("#documentline-vat").val() / 100) ));
	$("#documentline-extra_amount").trigger("change");
});

$("#documentline-quantity_virgule").change( function() {
	$("#documentline-quantity").val($("#documentline-quantity_virgule").val().replace(",","."));
	$("#documentline-quantity").trigger('change');
});

$("#documentline-unit_price_virgule").change( function() {
	$("#documentline-unit_price").val($("#documentline-unit_price_virgule").val().replace(",","."));
	$("#documentline-unit_price").trigger('change');
});

$("#documentline-vat_virgule").change( function() {
	$("#documentline-vat").val($("#documentline-vat_virgule").val().replace(",","."));
	$("#documentline-vat").trigger('change');
});

$("#documentline-extra_amount_virgule").change( function() {
	$("#documentline-extra_amount").val($("#documentline-extra_amount_virgule").val().replace(",","."));
	$("#documentline-extra_amount").trigger('change');
});

$("#documentline-work_height_virgule").change( function() {
	$("#documentline-work_height").val($("#documentline-work_height_virgule").val().replace(",","."));
	$("#documentline-work_height").trigger('change');
});

$("#documentline-work_width_virgule").change( function() {
	$("#documentline-work_width").val($("#documentline-work_width_virgule").val().replace(",","."));
	$("#documentline-work_width").trigger('change');
});

/**
 *	Changes in main order line
 *  Compute rebate/supplement
 */
$("#documentline-extra_amount, #documentline-extra_type").change( function() {
	extra_type = $("#documentline-extra_type").val();
	if(extra_type != '') {
		amount = parseFloat(extra_type.indexOf("PERCENT") > -1 ? $("#documentline-price_htva").val() * ($("#documentline-extra_amount").val()/100) : $("#documentline-extra_amount").val());
		if(amount > 0) {
			asigne = extra_type.indexOf("SUPPLEMENT_") > -1 ? 1 : -1;
			amount = arrondir2(asigne * amount);
			$("#documentline-extra_htva").val(amount);
			$("#documentline-final_htva").val(arrondir2(parseFloat($("#documentline-price_htva").val()) + amount));
			$("#documentline-final_tvac").val(arrondir2(parseFloat($("#documentline-final_htva").val()) * (1 + $("#documentline-vat").val() / 100)));
		} else if (amount == 0) {
			$("#documentline-extra_htva").val('');
			$("#documentline-final_htva").val('');
			$("#documentline-final_tvac").val('');
		}
	} else {
		$("#documentline-extra_htva").val('');
		$("#documentline-final_htva").val('');
		$("#documentline-final_tvac").val('');
	}
});


/**
 *	Unit Price Computations
 */

$('#documentlinedetail-price_chroma:enabled, #documentlinedetail-price_frame:enabled, #documentlinedetail-price_montage:enabled, #documentlinedetail-price_renfort:enabled').change(
function (event) {
	//console.log('change from CL'+event.target.id);
	$("#documentline-unit_price").val(
		  getVal('#documentlinedetail-price_chroma:enabled')
		+ getVal('#documentlinedetail-price_frame:enabled')
		+ getVal('#documentlinedetail-price_montage:enabled')
		+ getVal('#documentlinedetail-price_renfort:enabled')
	);
	$("#documentline-unit_price").trigger('change');
});

//#documentlinedetail-price_frame:enabled, #documentlinedetail-price_montage:enabled

$('#documentlinedetail-price_tirage:enabled, #documentlinedetail-price_support:enabled, #documentlinedetail-price_chassis:enabled, #documentlinedetail-price_protection:enabled').change(
function (event) {
	//console.log('change from FA:'+event.target.id);
	$("#documentline-unit_price").val(
		  getVal('#documentlinedetail-price_tirage:enabled')
		+ getVal('#documentlinedetail-price_support:enabled')
		+ getVal('#documentlinedetail-price_frame:enabled')
		+ getVal('#documentlinedetail-price_montage:enabled')
		+ getVal('#documentlinedetail-price_chassis:enabled')
		+ getVal('#documentlinedetail-price_protection:enabled')
	);
	$("#documentline-unit_price").trigger('change');
});


/**
 *	Options item price calculation
 */

/** generic price based on linear regression a x + b with x being either perimeter or surface in centimeters */
function price_linreg(w, h, a, b, use_surface) {
	qty = use_surface ? (w * h / 10000) : ((w + h) / 50); // 2 * (w + h) / 100 in meters, 100cmX100cm=10000cm2 in a m2
	return a * qty + b;
}

/** generic frame price based on perimeter and price per length */
function price_frame_meter(w, h, frame) {
	return price_linreg(w, h, frame.prix_de_vente, 0, false);
}

/** generic frame price based on perimeter and price per length */
function price_regression(w, h, item, use_surface) {
	param_name = item.reference;
	reg_a = getPrice(param_name+'_A');
	console.log('price_regression: a='+reg_a);
	reg_b = getPrice(param_name+'_B');
	console.log('price_regression: b='+reg_b);
	return price_linreg(w, h, reg_a, reg_b, use_surface);
}

/** frame */
function price_frame() {
	if(!(dim = getDimensions()))
		return;
	w = dim.width;
	h = dim.height;
	
	frame_id = parseInt($("#documentlinedetail-frame_id:enabled").val());
	console.log('frame: '+frame_id);
	if(frame_id > 0) {
		frame = getItemById(frame_id);
		if (typeof(frame) !== 'undefined') {
			price = 0;
			//console.log("frame_ref: "+frame.manufacturer+', '+frame.price);
			switch(frame.fournisseur) {
				case 'Nielsen':
					price = price_frame_meter(w, h, frame);
					break;
				case 'Exhibit':
					price = price_frame_exhibit(w, h, frame);
					break;
				case 'American Box':
					price = price_regression(w, h, frame, false);
					break;
			}
			//console.log('frame / refort price: '+price);
			$("#documentlinedetail-price_frame:enabled").val(arrondir_sup(price));
		} else {
			$("#documentlinedetail-price_frame:enabled").val('');
		}
	} else {
		$("#documentlinedetail-price_frame:enabled").val('');
	}
	price_renfort(); // if no frame, renfort is no longer free
	$("#documentlinedetail-price_frame:enabled").trigger('change');
}


/** renfort */
function price_renfort() {
	if(!(dim = getDimensions()))
		return;
	w = dim.width;
	h = dim.height;
	
	frame_id = parseInt($("#documentlinedetail-frame_id:enabled").val());
	//console.log('price_renfort: has frame? frame_id is '+frame_id);
	price = 0;
	if(frame_id > 0 && (w > store_values.param['RenfortMaxWidth'].value_int || h > store_values.param['RenfortMaxHeight'].value_int)) { // force renfort, but it is free
		$("#documentlinedetail-renfort_bool:enabled").prop('checked', 'checked');
		$("#documentlinedetail-renfort_bool").prop('readonly', true);
		$("#documentlinedetail-price_renfort:enabled").val(0);
	} else {
		$("#documentlinedetail-renfort_bool").prop('disabled', false);
		renfort = $("#documentlinedetail-renfort_bool:enabled").is(':checked');
		//console.log('price_renfort: place refort: '+renfort);
		if(renfort) {
			price = 2 * (w + h - 40) * getPrice('Renfort') / 100; // renfort placed 10cm inside, all perimeter
			RenfortPrixMin = getPrice('RenfortPrixMin');			
			if(price < RenfortPrixMin) price = RenfortPrixMin;
			price = arrondir_sup(price);
			$("#documentlinedetail-price_renfort:enabled").val(price);
		} else {
			$("#documentlinedetail-price_renfort:enabled").val('');
		}
	}
	$("#documentlinedetail-price_renfort:enabled").trigger('change');
}


/** Montage */
function price_montage() {
	montage = $("input[name='DocumentLineDetail[montage_bool]']").is(':checked');
	
	frame_id = parseInt($("#documentlinedetail-frame_id:enabled").val());
	//console.log('price_montage: has frame? frame_id is '+frame_id);
	price = 0;
	if(!isNaN(frame_id) && montage) {
		//console.log('montage:'+montage);
		if(!(dim = getDimensions()))
			return;
		w = dim.width;
		h = dim.height;
		price = (w + h) > store_values.param['LargeFrame'].value_int ? getPrice('Montage170L') : getPrice('Montage170S');
		//console.log('montage price:'+price);
		$("#documentlinedetail-price_montage:enabled").val(price);
	} else
		$("#documentlinedetail-price_montage:enabled").val('');

	$("#documentlinedetail-price_montage:enabled").trigger('change');
}

/** ChromaLuxe */
function price_chromaluxe() {
	if ($("#documentline-item_id").val() != store_values.item_id.chroma)
		return;

	if(!(dim = getDimensions(true)))
		return;

	which = $('input[name="DocumentLineDetail[chroma_id]"]:checked').val();
	//console.log('chromaluxe type:'+which);
	if(typeof(which) !== 'undefined') {
		del_error("CHROMALUXE_TYPE");
		max_area = store_values.param['SublimationMaxHeight'].value_int * store_values.param['SublimationMaxWidth'].value_int;
		work_area = dim.width * dim.height;
		del_error("SURFACE_TOO_LARGE");
		if(work_area <= store_values.param['ChromaLuxeXS'].value_number) {
			price = getPrice("ChromaXS") * work_area / max_area;
		} else if(work_area <= store_values.param['ChromaLuxeS'].value_number) {
			price = getPrice("ChromaS") * work_area / max_area;
		} else if(work_area <= store_values.param['ChromaLuxeM'].value_number) {
			price = getPrice("ChromaM") * work_area / max_area;
		} else if(work_area <= store_values.param['ChromaLuxeL'].value_number) {
			price = getPrice("ChromaL") * work_area / max_area;
		} else if(work_area <= max_area) {
			price = getPrice("ChromaXL") * work_area / max_area;
		} else {
			add_error("SURFACE_TOO_LARGE");
		}
		price = arrondir_sup(price);
		$("#documentlinedetail-price_chroma:enabled").val(price);
		$("#documentlinedetail-price_chroma:enabled").trigger('change');
	} else {
		add_error("CHROMALUXE_TYPE");
	}
}

/** Exhibit */
function price_frame_exhibit(w, h, frame) {
	// frame (len in cm, price in €/m)
	len = w + h;
	price = frame.prix_de_vente * len / 50;
	// adjustment
	base = (frame.reference == "Exhibite-X25Standard") ? getPrice('MontageExhibiteBase2') : getPrice('MontageExhibiteBase5'); // 
	if(w < 30 || h < 30) {
		price += base;
	} else if(len < 121) {
		price += base + (h-30 + w-30) * getPrice('MontageExhibiteS');		
	} else if (len < 130) {
		price += base + (h-30) * getPrice('MontageExhibiteMH') + (w-20) * getPrice('MontageExhibiteML');		
	} else {
		price += base + (h-30 + w-30) * getPrice('MontageExhibiteL');		
	}
	return arrondir(price);
}


/**
 *	jQuery hooks: If element changes, recompute order line prices
 */

/** Option-level changes */

/** ChromaLuxe */
$("#documentlinedetail-renfort_bool").change(function() {
	price_renfort();
});

$("input[name='DocumentLineDetail[montage_bool]']").change(function() {
	price_montage();
});

$("#documentlinedetail-frame_id:enabled").change(function() {
	//console.log('updated frame_id');
	frame_id = parseInt($("#documentlinedetail-frame_id:enabled").val());
	$("#documentlinedetail-montage_bool").prop('disabled', isNaN(frame_id));
	price_frame();
});

/** Fine arts */
$("#documentlinedetail-tirage_id:enabled").change(function() {
	// set tirage
	item_id = parseInt($(this).val());
	hideAllFineArt();

	if(isNaN(item_id)) { // none selected
		add_error("FINEART_NO_TIRAGE");
		$('#documentlinedetail-price_tirage:enabled').val('');
		$('#documentlinedetail-price_support:enabled').val('');
		$('#documentlinedetail-price_chassis:enabled').val('');
		$('#documentlinedetail-price_frame:enabled').val('');
		$('#documentlinedetail-price_montage:enabled').val('');
		$('#documentlinedetail-price_protection:enabled').val('');
		$('#documentlinedetail-price_tirage:enabled').trigger('change');
		return;
	}
	del_error("FINEART_NO_TIRAGE");
	
	item = getItemById(item_id);
	$('#documentlinedetail-price_tirage:enabled').val(item.prix_de_vente);
	// enable or disable options depending on paper type
	paper_type = item.fournisseur;
	//console.log('Paper type: '+paper_type);

	// 1. show what is necessary, 2. reset others option values, 3. trigger recalculation
	switch(paper_type) {
		case 'Papier Photo':
			$('div.field-documentlinedetail-finish_id').toggle(true);
			$('div.field-documentlinedetail-support_id').toggle(true);
			$('div.field-documentlinedetail-price_support').toggle(true);
			$('div.field-documentlinedetail-frame_id').toggle(true);
			$('div.field-documentlinedetail-price_frame').toggle(true);
			$('div.field-documentlinedetail-montage_bool').toggle(true);
			$('div.field-documentlinedetail-price_montage').toggle(true);

			$('#documentlinedetail-note:enabled').val('');
			$('#documentlinedetail-price_chassis:enabled').val('');
			$('#documentlinedetail-price_protection:enabled').val('');

			$("#documentlinedetail-price_montage:enabled").trigger('change');
			break;
		case 'Papier Fine Art':
			$('div.field-documentlinedetail-note').toggle(true);
			$('div.field-documentlinedetail-support_id').toggle(true);
			$('div.field-documentlinedetail-price_support').toggle(true);
			$('div.field-documentlinedetail-frame_id').toggle(true);
			$('div.field-documentlinedetail-price_frame').toggle(true);
			$('div.field-documentlinedetail-montage_bool').toggle(true);
			$('div.field-documentlinedetail-price_montage').toggle(true);
			$('div.field-documentlinedetail-protection_id').toggle(true);
			$('div.field-documentlinedetail-price_protection').toggle(true);

			$('div.field-documentlinedetail-finish_id input[type="radio"]').prop('checked',false);
			$('#documentlinedetail-price_chassis:enabled').val('');

			$("#documentlinedetail-price_protection:enabled").trigger('change');
			break;
		case 'Canvas':
			$('div.field-documentlinedetail-chassis_id').toggle(true);
			$('div.field-documentlinedetail-price_chassis').toggle(true);

			$('#documentlinedetail-note:enabled').val('');
			$('div.field-documentlinedetail-finish_id input[type="radio"]').prop('checked',false);
			$('#documentlinedetail-price_support:enabled').val('');
			$('#documentlinedetail-price_frame:enabled').val('');
			$('#documentlinedetail-price_montage:enabled').val('');
			$('#documentlinedetail-price_protection:enabled').val('');

			$("#documentlinedetail-price_chassis:enabled").trigger('change');
			break;
		default: /* sans tirage */
			$('div.field-documentlinedetail-support_id').toggle(true);
			$('div.field-documentlinedetail-price_support').toggle(true);
			$('div.field-documentlinedetail-frame_id').toggle(true);
			$('div.field-documentlinedetail-price_frame').toggle(true);
			$('div.field-documentlinedetail-montage_bool').toggle(true);
			$('div.field-documentlinedetail-price_montage').toggle(true);
			$('div.field-documentlinedetail-protection_id').toggle(true);
			$('div.field-documentlinedetail-price_protection').toggle(true);

			$('#documentlinedetail-note:enabled').val('');
			$('#documentlinedetail-price_chassis:enabled').val('');

			$("#documentlinedetail-price_montage:enabled").trigger('change');
			break;
	}
	$('#documentlinedetail-price_tirage:enabled').trigger('change');
});

$("#documentlinedetail-support_id:enabled").change(function() {
	//console.log('udated support');
	item_id = parseInt($("#documentlinedetail-support_id:enabled").val());
	if(isNaN(item_id)) { // none selected
		$('#documentlinedetail-price_support:enabled').val('');
	} else {
		if(!(dim = getDimensions()))
			return;
		w = dim.width;
		h = dim.height;

		item = getItemById(item_id);
		price = arrondir_sup(price_regression(w, h, item, true));
		$('#documentlinedetail-price_support:enabled').val(price);
	}
	$("#documentlinedetail-price_support:enabled").trigger('change');
});

$("#documentlinedetail-protection_id:enabled").change(function() {
	item_id = parseInt($(this).val());
	if(isNaN(item_id)) { // none selected
		$('#documentlinedetail-price_protection:enabled').val('');
	} else {
		item = getItemById(item_id);
		$('#documentlinedetail-price_protection:enabled').val(item.prix_de_vente);
	}
	$('#documentlinedetail-price_protection:enabled').trigger('change');
});

$("#documentlinedetail-chassis_id:enabled").change(function() {
	item_id = parseInt($(this).val());
	//console.log('chassis='+item_id);
	if(isNaN(item_id)) { // none selected
		$('#documentlinedetail-price_chassis:enabled').val('');
	} else {
		item = getItemById(item_id);
		//console.log('got chassis='+item.id);
		$('#documentlinedetail-price_chassis:enabled').val(item.prix_de_vente);
	}
	$('#documentlinedetail-price_chassis:enabled').trigger('change');
});

/** Item-level changes */
$("input[name='DocumentLineDetail[chroma_id]']").change(function() {
	//console.log('updated chroma_id');
	price_chromaluxe();
	price_frame();
	price_montage();
	price_renfort();
});

$("#documentline-work_width, #documentline-work_height").change(function(event) {
	item_id = parseInt($("#documentline-item_id").val());
	if(item_id != store_values.item_id.chroma && item_id != store_values.item_id.fineart && item_id != store_values.item_id.freeitem)
		return;

	//console.log('updated size from '+event.target.id);
	price_chromaluxe();
	price_frame();
	price_montage();
	price_renfort();
	$("#documentlinedetail-support_id:enabled").trigger('change');
});


/**
 * Cosmetics and UI function
 */
function hideAllFineArt() { // except "tirage"
	$('div.field-documentlinedetail-finish_id').toggle(false);
	$('div.field-documentlinedetail-note').toggle(false);
	$('div.field-documentlinedetail-support_id').toggle(false);
	$('div.field-documentlinedetail-price_support').toggle(false);
//	$('div.field-documentlinedetail-frame_id').toggle(false);
//	$('div.field-documentlinedetail-price_frame').toggle(false);
//	$('div.field-documentlinedetail-montage_bool').toggle(false);
//	$('div.field-documentlinedetail-price_montage').toggle(false);
	$('div.field-documentlinedetail-chassis_id').toggle(false);
	$('div.field-documentlinedetail-price_chassis').toggle(false);
	$('div.field-documentlinedetail-protection_id').toggle(false);
	$('div.field-documentlinedetail-price_protection').toggle(false);
}


/**
 *	Item with free name, price, and VAT
 */
$("#documentlinedetail-free_item_libelle:enabled").blur(function() {
	if($("#documentlinedetail-free_item_libelle:enabled").val() == '')
		add_error("FREEITEM_NO_DESCRIPTION");
	else
		del_error("FREEITEM_NO_DESCRIPTION");
});

$("#documentlinedetail-free_item_libelle:enabled").change(function() {
	if($("#documentlinedetail-free_item_libelle:enabled").val() == '')
		add_error("FREEITEM_NO_DESCRIPTION");
	else
		del_error("FREEITEM_NO_DESCRIPTION");
});

$("#documentlinedetail-free_item_price_htva:enabled, #documentlinedetail-free_item_vat:enabled").change(function() {
	price = parseFloat($("#documentlinedetail-free_item_price_htva:enabled").val().replace(",","."));
	if(isNaN(price))
		add_error("FREEITEM_NO_PRICE");
	else {
		del_error("FREEITEM_NO_PRICE");
		$("#documentline-unit_price").val(price);
	}
	
	vat = parseFloat($("#documentlinedetail-free_item_vat:enabled").val().replace(",","."));
	if(isNaN(vat))
		$("#documentline-vat").val(0);
	else {
		$("#documentline-vat").val(vat);
	}

	if($("#documentlinedetail-free_item_libelle:enabled").val() == '')
		add_error("FREEITEM_NO_DESCRIPTION");

	$("#documentline-unit_price").trigger('change');
});

function free_item_update() {
	$("#documentline-unit_price_virgule").prop('readonly', false);
	$("#documentline-vat_virgule").prop('readonly', false);
	$("#documentlinedetail-free_item_libelle:enabled").trigger('blur');
}

/**
 *	Enable/disable fields depending on item.
 *  Important notes
 *		- Disabled form fields are not submited (HTML rule)
 *  	- This form contains an hardcoded form name
 */
$('#documentline-form').submit(function(e) {
	has_error = $("#store-missing-data").is(":visible");
	//console.log('has error? '+ has_error);
	if( has_error )
		e.preventDefault();
	else
        return;
});

$(".order-option").click(function () {
	item_id   = $(this).data('item_id');
	item_name = $(this).data('item_name');
	item_vat  = $(this).data('item_vat');
	$("#documentline-item_id").val(item_id);
	$("#documentline-vat").val(item_vat);
	
	///ATTENTION SELECT_DISPLAY_ID HARDCODED HERE
	test = $('#select2-chosen-2');
	if($('#select2-chosen-2').length != 0)
		$('#select2-chosen-2').html(item_name);
	else
		$('#select2-chosen-1').html(item_name);

	class_prefix = '.'+store_values.class_prefix;

	$(class_prefix+store_values.item_id.chroma).prop('disabled', true);
	$(class_prefix+store_values.item_id.fineart).prop('disabled', true);
	$(class_prefix+store_values.item_id.freeitem).prop('disabled', true);

	$(class_prefix+item_id).prop('disabled', false);

	$(class_prefix+store_values.item_id.chroma).find('input').prop('disabled', true);
	$(class_prefix+store_values.item_id.fineart).find('input').prop('disabled', true);
	$(class_prefix+store_values.item_id.freeitem).find('input').prop('disabled', true);

	$(class_prefix+item_id).find('input').prop('disabled', false);

	clean_errors();
	switch(parseInt(item_id)) {
		case store_values.item_id.chroma:
			$("#documentlinedetail-frame_id:enabled").trigger('change');
			break;
		case store_values.item_id.fineart:
			$("#documentlinedetail-tirage_id:enabled").trigger('change');	
			break;
		case store_values.item_id.freeitem:
			$("#documentlinedetail-free_item_price_htva:enabled").trigger('change');	
			break;
	} // other items: no nothing

	//console.log('item '+item_id+' set');
});
/**
 *	I N I T
 */
clean_errors();