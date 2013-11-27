$(document).on('click', ".annuler-abonnement", function() {
	var date_abo = $(this).closest('tr').attr('data-date');	
	var route_abo = Routing.generate("majordesk_app_annuler_abonnement", {'id_elevematiere' : $(this).closest('tr').attr('data-id-elevematiere')});
	$('#date-fin-abo').html(date_abo);
	$('a.annuler-abonnement-lien').attr('href', route_abo);
 });