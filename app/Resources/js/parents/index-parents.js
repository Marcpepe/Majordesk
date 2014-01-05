$('.datepicker').datepicker({'format' : 'dd/mm/yyyy', 'language' : 'fr', 'weekStart' : '1', 'autoclose' : 'true'});

$('#ticketselectnofiltretype_eleve').change( function() {
	var id_eleve = $('#ticketselectnofiltretype_eleve').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_matieres", {'id_eleve' : id_eleve}),
		success: function(data){
			$('#matieresselectortype_matiere').html(data.html);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_professeurs", {'id_eleve' : id_eleve}),
		success: function(data){
			$('#professeurselectortype_professeur').html(data.html);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});