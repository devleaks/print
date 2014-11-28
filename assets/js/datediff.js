function parseDate(str) { // str like '2014-10-27'
    var ymd = str.split('-');
    return new Date(ymd[0], ymd[1]-1, ymd[2]);
}
function daydiff(first, second) {
    return Math.floor( (second-first)/(1000*60*60*24) ) + 1;
}
$("#order-due_date").change(function() {
	then = parseDate($(this).val());
	days = daydiff(new Date(), then); // values to be loaded from db...
	     if (days < 3) message = "danger";
	else if (days < 5) message = "warning";
	else if (days < 7) message = "info";
	else               message = "success";
	console.log('adding '+message);
	$('#daysComputed').val(days).parent()
		.removeClass('bg-danger')
		.removeClass('bg-warning')
		.removeClass('bg-info')
		.removeClass('bg-success')
		.addClass('bg-'+message);
	
});