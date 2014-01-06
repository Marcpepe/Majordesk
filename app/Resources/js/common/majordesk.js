/**
  * ELEVE
  */

/* Control Bar
 * =========== */

$('#control-en-cours').popover({
			title: 'Tu es en cours',
			placement: 'bottom',
			container: 'header.navbar',
			html: 'true',
});

$(document).on('click', 'button.fixer-prochain-cours', function() {
	bootbox.confirm("<i class='icon-question-sign icon-large text-success'></i> <strong>Confirmation</strong> <br><br>Terminer le cours et fixer la date du prochain cours ?", 'Non', 'Oui', function(result) {
		if (result) {
			window.location.href = Routing.generate('majordesk_app_calendrier_des_cours', {'etape':3});
		}
		else {
			$('#control-favoris').removeClass('icon-spin');
		}
	});
});

/**
  * COMMON
  */

/* Alerts
 * ====== */

$(document)
.on('click','.close-majorclass', function() {
	$('.alert-majorclass').fadeOut(300, function() { $(this).remove(); });
})
.on('click','.confirm-majorclass', function() {
	var message = $(this).attr('data-message');
	// $(this).closest('form').submit(function() {
		return confirm(message);
	// });
});