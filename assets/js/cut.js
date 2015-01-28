/** S U B R O U T I N E S
 *
 */
var handleString = '<div class="master-info"><span class="glyphicon glyphicon-move handle"></span>#<span class="master-number">#</span> <meter min="0" max="200" value="0"></meter> <span class="master-work_length">0</span> <input type="checkbox" class="masterKeep" name="masterKeep" value="XXXMASTER_IDXXX"> <span class="master-work_used">0</span></div>';

function totalLength($elem) {
	var sum = 0;
	$elem.children('li').each(function() {
		sum += parseFloat($(this).data('work_length'));
	});
	return sum;
}

function initSortable(element) {
	
	element.sortable({
		items: 'li',
		connectWith: ".master",
		placeholder: "placeholder",
		start: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		stop: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		receive: function(event, ui) {
				var sum = totalLength($(this));
				var max = $(this).data('work_length');
				if (sum > max) {
					//console.log('would have '+$(this).children('li').length+' items, cancelling...');
					$(ui.sender).sortable('cancel');
				} else {
					if($(this).hasClass('new')) {
						//console.log('create new');
		                var clone = $(this).clone();
		                $(this).removeClass('new');
						now = new Date();
						newId = 'master-new-'+now.getTime();
						$(this).attr('id', newId);
		                clone.empty();
						initSortable(clone);
		                $(this).parent().after(clone.wrap('<li></li>').parent());
						thisHandle = handleString.replace('XXXMASTER_IDXXX', newId);
						$(this).prepend(handleString);
					}
					cleanUp();
				}
			}
	});

}

function cleanUp() {	
	var cnt = 1;
    $('.master').not('.new').each(function() {
		var sum = totalLength($(this));
		var max = $(this).data('work_length');
		if(sum == max) {
			$(this).addClass("master-full");
		} else if(sum < max) {
			$(this).removeClass("master-full");
		} else if(sum > max) {
			$(this).addClass("master-error");
		}
		if(sum === 0) {
			$(this).parent().remove();
		} else {
			val = Math.round(sum, 2);
			$(this).parent().find('span.master-number').text(cnt++);
			$(this).parent().find('span.master-work_used').text(val);
			$(this).parent().find('meter').attr('value',val);
		}
	});
}


function saveCuts() {
	var masters = new Array();
	$( ".master" ).not('.new').each(function() {
		masters.push( {
			id: $(this).attr('id'),
			segments: $(this).sortable( "toArray" )
		} );
	});
	cuts = JSON.stringify(masters);

	keeps = new Array();
	
	$( "input[name='masterKeep']:checked" ).each(function() {
		keeps.push( $(this).val() );
	});
	console.log(JSON.stringify(keeps));
	
	$.ajax({
		type: "POST",
		url: jsonURL.save,
		dataType: 'json',
		async: !1,
		data: {
			cuts: cuts,
			keeps: JSON.stringify(keeps)
		},
		success: function(data) {
			$('#savedResults').text(data.result);
		},
		error: function(data) {
			console.log(data);
		},
	});

}


function split(ref, size) {
	id = ref.replace('R-','');
	newid = null;
	$.ajax({
		type: "GET",
		url: jsonURL.split,
		dataType: 'json',
		async: !1,
		data: {
			id: id,
			size: size
		},
		success: function(data) {
			newid = data.result;
		},
		error: function(data) {
			console.log(data);
		},
	});
	//console.log("newid="+newid);
	return newid;
}


function splitCut() {
	which = $("#splitCut").val();
	newsize  = $("#splitSize").val();
	newSegment = split(which, newsize);
	if(newSegment != null) {
		oldsize = $("#"+which).data('work_length');
		// adjust old
		$("#"+which).data('work_length', oldsize - newsize)
		newdesc = $("#"+which).attr('id')+' '+$("#"+which).data('work_length')+'cm';
		$("#"+which).html(newdesc).addClass('split');
		$("#"+which).parent().append('<li class="segment" id="R-'+newSegment+'" data-work_length="'+newsize+'"></li>');
		$("#R-"+newSegment).html('R-'+newSegment+'- '+newsize+'cm').addClass('split');
	}	
}


/** M A I N
 *
 */

/** To change order of masters */
$("#master-case").sortable({
	handle : '.handle',
	cursor: 'move',
	stop: function (event, ui) {
		cleanUp();
	},
});

function js_cuts_init() {
initSortable($(".master"));
$(".master").not('.new').each(function() {
	$(this).prepend(handleString.replace('XXXMASTER_IDXXX', $(this).attr('id')));
	master_len = $(this).data('work_length')
	$(this).find('span.master-work_length').text(master_len);
	$(this).find('meter').attr('max', master_len);
});
$(".segment").each(function() {
	desc = $(this).attr('id')+' '+$(this).data('work_length')+'cm';
	$("#splitCut").append('<option value="'+$(this).attr('id')+'">'+$(this).attr('id')+'</option>');
	$(this).html(desc);
});
/** Initialization on first run */
cleanUp();

}