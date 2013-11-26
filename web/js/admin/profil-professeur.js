var id_professeur = parseInt($('#id-professeur').html());
$("#flag").click( function() {
	$("#flag").html('<i class="icon-spinner icon-spin"></i>');
	$.ajax({
		type: "POST",
		timeout: 5000,
		url: Routing.generate('majordesk_app_toggle_flag_professeur', {'id' : id_professeur}),
		success: function(){
			$("#flag").html('<i class="icon-flag"></i>');   
			if ( $(".flag").hasClass("warning") )
			{
				$(".flag").removeClass("warning");
				$(".flag").children("td:last-child").html("Non");
			}
			else
			{
				$(".flag").addClass("warning");
				$(".flag").children("td:last-child").html('Oui<i class="icon-flag icon-large pull-right text-yellow"></i>');
			}
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});
$("#actif").click( function() {
	$("#actif-trigger").html('<i class="icon-spinner icon-spin"></i>');
	$.ajax({
		type: "POST",
		timeout: 5000,
		url: Routing.generate('majordesk_app_toggle_actif_professeur', {'id' : id_professeur}),
		success: function(){
			if ( $(".actif").hasClass("danger") )
			{
				$(".actif").removeClass("danger");
				$(".actif").children("td:last-child").html("Non");
			}
			else
			{
				$(".actif").addClass("danger");
				$(".actif").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
			}
			$("#actif-trigger").html('<i class="icon-ban-circle"></i>');
		},
		error: function() {
			$("#actif-trigger").html('<i class="icon-ban-circle"></i>');
			alert('La requête n\'a pas abouti');
		}
	});
});