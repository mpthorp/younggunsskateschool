$(document).foundation();

$( document ).ready(function() {

	$('.book').click(function(){
		$("#mce-TYPE").val("Make a booking");
	});

	$('.enquire').click(function(){
		$("#mce-TYPE").val("General Enquiry");
	});
});