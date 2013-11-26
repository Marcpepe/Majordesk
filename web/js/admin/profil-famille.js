var id_famille = parseInt($('#id-famille').html());	
$("#flag").click( function() {
	$("#flag").html('<i class="icon-spinner icon-spin"></i>');
	$.ajax({
		type: "POST",
		timeout: 5000,
		url: Routing.generate('majordesk_app_toggle_flag_famille', {'id' : id_famille}),
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
		url: Routing.generate('majordesk_app_toggle_actif_famille', {'id' : id_famille}),
		success: function(){
			if ( $(".actif").hasClass("danger") )
			{
				$(".actif").removeClass("danger");
				$(".actif").children("td:last-child").html("Non");
				$(".actif-1").removeClass("danger");
				$(".actif-1").children("td:last-child").html("Non");
				$(".actif-2").removeClass("danger");
				$(".actif-2").children("td:last-child").html("Non");
			}
			else
			{
				$(".actif").addClass("danger");
				$(".actif").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
				$(".actif-1").addClass("danger");
				$(".actif-1").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
				$(".actif-2").addClass("danger");
				$(".actif-2").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
			}
			$("#actif-trigger").html('<i class="icon-ban-circle"></i>');
		},
		error: function() {
			$("#actif-trigger").html('<i class="icon-ban-circle"></i>');
			alert('La requête n\'a pas abouti');
		}
	});
});
$("#actif-1").click( function() {
	var id_parent = parseInt($('#id-parent-1').html());
	$("#actif-trigger-1").html('<i class="icon-spinner icon-spin"></i>');
	$.ajax({
		type: "POST",
		timeout: 5000,
		url: Routing.generate('majordesk_app_toggle_actif_parent', {'id' : id_parent}),
		success: function(){
			if ( $(".actif-1").hasClass("error") )
			{
				$(".actif-1").removeClass("error");
				$(".actif-1").children("td:last-child").html("Non");
			}
			else
			{
				$(".actif-1").addClass("error");
				$(".actif-1").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
			}
			$("#actif-trigger-1").html('<i class="icon-ban-circle"></i>');
		},
		error: function() {
			$("#actif-trigger-1").html('<i class="icon-ban-circle"></i>');
			alert('La requête n\'a pas abouti');
		}
	});
});
$("#actif-2").click( function() {
	var id_parent = parseInt($('#id-parent-2').html());
	$("#actif-trigger-2").html('<i class="icon-spinner icon-spin"></i>');
	$.ajax({
		type: "POST",
		timeout: 5000,
		url: Routing.generate('majordesk_app_toggle_actif_parent', {'id' : id_parent}),
		success: function(){
			if ( $(".actif-2").hasClass("error") )
			{
				$(".actif-2").removeClass("error");
				$(".actif-2").children("td:last-child").html("Non");
			}
			else
			{
				$(".actif-2").addClass("error");
				$(".actif-2").children("td:last-child").html('Oui<i class="icon-ban-circle icon-large pull-right text-red"></i>');
			}
			$("#actif-trigger-2").html('<i class="icon-ban-circle"></i>');
		},
		error: function() {
			$("#actif-trigger-2").html('<i class="icon-ban-circle"></i>');
			alert('La requête n\'a pas abouti');
		}
	});
});