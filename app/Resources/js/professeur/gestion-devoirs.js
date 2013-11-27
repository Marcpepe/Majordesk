$('button.reinit').click(function() {
	var thisTd = $(this).parent('td');
	var id_exercice = thisTd.attr('data-id-exercice');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous remettre à zéro cet exercice ?", 'Non', 'Oui', function(result) {
		if (result) {	
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_reinitialiser_exercice", {'id_exercice' : id_exercice}),
				success: function(){
					thisTd.prev().prev().text('00min00s');
					thisTd.prev().prev().prev().text('0');
					thisTd.siblings().first().html('<i class="icon-remove text-light-grey" rel="tooltip" data-title="Non commencé"></i>');
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}	
	});
});