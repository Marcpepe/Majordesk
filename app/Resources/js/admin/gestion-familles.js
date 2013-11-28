$(document).on('click', 'a.flag', function() {
	var tag = $(this);
	tag.closest("td").prev().prev().html('<i class="icon-spinner icon-large icon-spin"></i>');
	
	var id = tag.closest("tr").attr('data-id');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_toggle_flag_famille", {'id' : id}),
		success: function(){
			if ( tag.closest("tr").hasClass("warning") )
			{
				tag.closest("td").prev().prev().html(" - ");
				tag.closest("tr").removeClass("warning");
				tag.html('<i class="icon-flag"></i>  Mettre un flag sur cette famille');
			}
			else
			{
				tag.closest("td").prev().prev().html('<i class="icon-flag icon-large text-yellow"></i>');
				tag.closest("tr").addClass("warning");
				tag.html('<i class="icon-flag"></i>  Retirer le flag de cette famille');
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
		url: Routing.generate("majordesk_app_toggle_actif_famille", {'id' : id}),
		success: function(){
			if (tag.closest("td").prev().hasClass("actif"))
			{
				tag.closest("td").prev().html('<i class="icon-ban-circle icon-large text-red"></i> ');
				tag.html('<i class="icon-ban-circle"></i>  Débloquer cette famille');
				tag.closest("td").prev().removeClass("actif");
			}
			else
			{
				tag.closest("td").prev().html(" - ");
				tag.html('<i class="icon-ban-circle"></i>  Bloquer cette famille');	
				tag.closest("td").prev().addClass("actif");
			}
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
})
.on('click', ".remove-famille", function() {
	var thisSelector = $(this);
	var ligne_famille = thisSelector.closest(".ligne_famille");
	var id = ligne_famille.attr('data-id');
	ligne_famille.find('td .spinner').html('<i class="icon-spinner icon-spin icon-large text-red"></i>');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer définitivement cette famille ?", 'Non', 'Oui', function(result) {
		if (result) {
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_delete_famille", {'id' : id}),
				success: function(){
					ligne_famille.fadeOut(400, function() { $(this).remove(); });
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}
		else {
			ligne_famille.find('td .spinner').html('');
		}
	});	
});