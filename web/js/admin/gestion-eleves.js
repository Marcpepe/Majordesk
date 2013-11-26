$(document).on('click', 'a.flag', function() {
	var tag = $(this);
	tag.closest("td").prev().prev().html('<i class="icon-spinner icon-large icon-spin"></i>');
	
	var id = tag.closest("tr").attr('data-id');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_toggle_flag_eleve", {'id' : id}),
		success: function(){
			if ( tag.closest("tr").hasClass("warning") )
			{
				tag.closest("td").prev().prev().html(" - ");
				tag.closest("tr").removeClass("warning");
				tag.html('<i class="icon-flag"></i>  Mettre un flag sur cet élève');
			}
			else
			{
				tag.closest("td").prev().prev().html('<i class="icon-flag icon-large text-yellow"></i>');
				tag.closest("tr").addClass("warning");
				tag.html('<i class="icon-flag"></i>  Retirer le flag de cet élève');
			}
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
})
.on('click', 'a.actif', function() {
	var tag = $(this);
	tag.closest("td").prev().html('<i class="icon-spinner icon-large icon-spin"></i>');
	
	var id = tag.closest("tr").attr('data-id');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_toggle_actif_eleve", {'id' : id}),
		success: function(){
			if (tag.closest("td").prev().hasClass("actif"))
			{
				tag.closest("td").prev().html('<i class="icon-ban-circle icon-large text-red"></i> ');
				tag.html('<i class="icon-ban-circle"></i>  Débloquer cet élève');
				tag.closest("td").prev().removeClass("actif");
			}
			else
			{
				tag.closest("td").prev().html(" - ");
				tag.html('<i class="icon-ban-circle"></i>  Bloquer cet élève');	
				tag.closest("td").prev().addClass("actif");
			}
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});