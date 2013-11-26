$(document).on('click', '.timepicker-alerte', function() {
	$(this).timepicker({
		minuteStep: 15,
		showInputs: false,
		showMeridian: false,
		defaultTime: '19:00',
		disableFocus: true
	});
});
$('.datepicker').datepicker({'format' : 'dd/mm/yyyy', 'language' : 'fr', 'weekStart' : '1', 'autoclose' : 'true'});