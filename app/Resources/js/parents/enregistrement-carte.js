$('#accepted').hide();
$('#cgv-box').change(function() {
	if ( $('#cgv-box').prop('checked') ) {
		$('#accepted').show();
		$('#refused').hide();
	} else {
		$('#accepted').hide();
		$('#refused').show();
	}
});