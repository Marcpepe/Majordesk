$(document).on('click', '.update-regle', function() {
	thisButton = $(this);
	var regle = thisButton.attr('data-regle');
	var id_ticket = thisButton.closest('td').attr('data-id-ticket');
	//thisButton.button('loading');
	$.ajax({
		type: "POST",
		data: { 'regle' : regle },
		url: Routing.generate("majordesk_app_update_regle", {'id_ticket' : id_ticket}),
		success: function(){
			//thisButton.button('reset');
			if (regle == 0) {		
				thisButton.closest('td').prev().html('<em>Non</em>');
				thisButton.closest('td').html('<div class="btn-group"><button class="btn btn-default update-regle" data-regle="1"><i class="icon-ok text-emerald"></i></button><button class="btn btn-danger active"><i class="icon-remove"></i></button></div>');
			} else if (regle == 1) {
				thisButton.closest('td').prev().html('<em>Oui</em>');
				thisButton.closest('td').html('<div class="btn-group"><button class="btn btn-success active"><i class="icon-ok"></i></button><button class="btn btn-default update-regle" data-regle="0"><i class="icon-remove text-pomegranate"></i></button></div>');
			} else {
				alert('Echec.');
			}
		},
		error: function() {
			//thisButton.button('reset');
			alert('Echec de la mise à jour du statut.');
		}
	});
});