$(document).on('click', '.update-feedback', function() {
	thisButton = $(this);
	var statut = thisButton.attr('data-statut');
	var id_feedback = thisButton.closest('tr').attr('data-id-feedback');
	thisButton.button('loading');
	$.ajax({
		type: "POST",
		data: { 'statut' : statut },
		url: Routing.generate("majordesk_app_update_feedback", {'id_feedback' : id_feedback}),
		success: function(){
			thisButton.button('reset');
			thisButton.closest('td').children('i').remove();
			if (statut == 0) {		
				thisButton.closest('td').prepend('<i rel="tooltip" data-title="Réfusé" class="icon-remove icon-large text-red"></i>');
			} else if (statut == 2) {
				thisButton.closest('td').prepend('<i rel="tooltip" data-title="Accepté et traité" class="icon-ok icon-large text-green"></i>');
			} else {
				thisButton.closest('td').prepend('<i rel="tooltip" data-title="En cours de traitement" class="icon-spinner icon-spin icon-large text-orange"></i>');
			}
		},
		error: function() {
			thisButton.button('reset');
			alert('Echec de la mise à jour du statut du feedback.');
		}
	});
});