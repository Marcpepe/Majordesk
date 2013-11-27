/* Populating chapitres
 * ==================== */

$('#exercicesselectortype_programme').change( function() {
	var id_matiere = $('#exercicesselectortype_matiere').val();
	var id_programme = $('#exercicesselectortype_programme').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
		success: function(data){
			$('#exercicesselectortype_chapitre').html(data.html);
			$('#exercicesselectortype_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
			$('#exercices-en-ligne-selected').html('');
		},
		error: function() {
			alert('Sélectionner une matière');
		}
	});
});

$('#exercicesselectortype_matiere').change( function() {
	var id_matiere = $('#exercicesselectortype_matiere').val();
	var id_programme = $('#exercicesselectortype_programme').val();
	if (id_programme !== null) {
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
			success: function(data){
				$('#exercicesselectortype_chapitre').html(data.html);
				$('#exercicesselectortype_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
				$('#exercices-en-ligne-selected').html('');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	}
});

/* Populating parties
 * ================== */

$('#exercicesselectortype_chapitre').change( function() {
	var id_chapitre = $('#exercicesselectortype_chapitre').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_parties", {'id_chapitre' : id_chapitre}),
		success: function(data){
			$('#exercicesselectortype_partie').html(data.html);
			$('#exercices-en-ligne-selected').html('');
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});

/* Filtering exercices
 * =================== */
 
$('#exercicesselectortype_partie').change( function() {
	var id_partie = $('#exercicesselectortype_partie').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_filter_exercices", {'id_partie' : id_partie}),
		success: function(exercices){
			$('#badge-en-ligne-selection').html(exercices.length);	
			var exercicePreview = '';
			if (exercices.length > 0) {		
				for (var i = 0; i < exercices.length; i++) {
					switch(exercices[i].matiere) {
						case 'Mathématiques':
							var label = 'label-info';
							break;
						case 'Physique-Chimie':
							var label = 'label-success';
							break;
						case 'Biologie':
							var label = 'label-important';
							break;
						case 'Anglais':
							var label = 'label-danger';
							break;
						case 'Français':
							var label = 'label-inverse';
							break;
						default:
							var label = '';
							break;
					}
					exercicePreview = exercicePreview+'<div class="exercice-en-ligne panel panel-default clearfix" data-id="' + exercices[i].id + '">'+
														'<div class="panel-heading"><strong>Id :</strong> ' + exercices[i].id + '<br></div>'+
														'<div class="panel-body">'+
															'<div class="pull-left">'+
																'<strong>Matière :</strong> <span class="label ' + label + '">' + exercices[i].matiere + '</span><br>'+
																'<strong>Programme :</strong> ' + exercices[i].programme + '<br>'+
																'<strong>Chapitre :</strong> ' + exercices[i].chapitre + '<br>'+
																'<strong>Partie :</strong> ' + exercices[i].partie + '<br>'+
																'<strong>Niveau :</strong> ' + exercices[i].niveau +
															'</div>'+	
															'<div class="btn-group pull-right">'+		
																'<a rel="tooltip" data-title="Afficher l\'exercice" class="btn btn-default" href="' + Routing.generate('majordesk_app_afficher_exercice', {'id' : exercices[i].id}) + '"><i class="icon-eye-open"></i></a>'+															
																'<button rel="tooltip" data-title="Mettre l\'exercice hors ligne" class="btn btn-default download-exercice"><i class="icon-cloud-download"></i></button>'+			  
															'</div>'+
														'</div>'+
													  '</div>';
				}	
			}
			$('#exercices-en-ligne-selected').html(exercicePreview);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});

/* Exercices management
 * ==================== */

$(document)

// Supprimer un exercice
.on('click', ".remove-exercice", function() {
	var thisSelector = $(this);
	thisSelector.html('<i class="icon-spinner icon-spin"></i>');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer définitivement cet exercice ?", 'Non', 'Oui', function(result) {
		if (result) {
			var exercice = thisSelector.closest(".exercice-en-edition");
			var id = exercice.attr('data-id');
			
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_delete_exercice", {'id' : id}),
				success: function(){
					exercice.fadeOut(400, function() { $(this).remove(); });
					$('#badge-en-edition').html( parseInt($('#badge-en-edition').html()) - 1);
					$('[rel=fix-tooltip]').tooltip('hide');
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}
		else {
			thisSelector.html('<i class="icon-trash"></i>');
		}
	});	
})

// Mettre un exercice en ligne
.on('click', ".upload-exercice", function() {
	var thisSelector = $(this);
	thisSelector.html('<i class="icon-spinner icon-spin"></i>');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous mettre cet exercice en ligne ?", 'Non', 'Oui', function(result) {
		if (result) {
			var exercice = thisSelector.closest(".exercice-en-edition");
			var id = exercice.attr('data-id');
			
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_upload_exercice", {'id' : id}),
				success: function(){
					exercice.fadeOut(400, function() { $(this).remove(); });
					$('#badge-en-edition').html( parseInt($('#badge-en-edition').html()) - 1);
					$('#badge-en-ligne').html( parseInt($('#badge-en-ligne').html()) + 1);
					$('[rel=fix-tooltip]').tooltip('hide');
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}
		else {
			thisSelector.html('<i class="icon-cloud-upload"></i>');
		}
	});	
})

// Enlever un exercice du contenu en ligne
.on('click', ".download-exercice", function() {
	var thisSelector = $(this);
	thisSelector.html('<i class="icon-spinner icon-spin"></i>');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous enlever cet exercice du contenu en ligne ?<br>(Cet exercice sera alors en édition, mais ne sera plus consultable en ligne.)", 'Non', 'Oui', function(result) {
		if (result) {
			var exercice = thisSelector.closest(".exercice-en-ligne");
			var id = exercice.attr('data-id');
			
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_download_exercice", {'id' : id}),
				success: function(exerciceData){
					exercice.fadeOut(400, function() { $(this).remove(); });						
					$('#badge-en-ligne').html( parseInt($('#badge-en-ligne').html()) - 1);		
					$('#badge-en-ligne-selection').html( parseInt($('#badge-en-ligne-selection').html()) - 1);		
					switch(exerciceData.matiere) {
						case 'Mathématiques':
							var label = 'label-info';
							break;
						case 'Physique-Chimie':
							var label = 'label-success';
							break;
						case 'Biologie':
							var label = 'label-important';
							break;
						case 'Anglais':
							var label = 'label-danger';
							break;
						case 'Français':
							var label = 'label-inverse';
							break;
						default:
							var label = '';
							break;
					}
					var exercicePreview = '<div class="exercice-en-edition panel panel-success clearfix" data-id="' + exerciceData.id + '">'+
											'<div class="panel-heading"><strong>Id :</strong> ' + exerciceData.id + '<br></div>'+
											'<div class="panel-body">'+
												'<div class="pull-left">'+
													'<strong>Matière :</strong> <span class="label ' + label + '">' + exerciceData.matiere + '</span><br>'+
													'<strong>Programme :</strong> ' + exerciceData.programme + '<br>'+
													'<strong>Chapitre :</strong> ' + exerciceData.chapitre + '<br>'+
													'<strong>Partie :</strong> ' + exerciceData.partie + '<br>'+
													'<strong>Niveau :</strong> ' + exerciceData.niveau +
												'</div>'+	
												'<div class="btn-group pull-right">'+
													'<a rel="tooltip" data-title="Afficher l\'exercice" class="btn btn-default" href="' + Routing.generate('majordesk_app_afficher_exercice', {'id' : exerciceData.id}) + '"><i class="icon-eye-open"></i></a>'+					
													'<a rel="tooltip" data-title="Modifier l\'exercice" class="btn btn-default" href="' + Routing.generate('majordesk_app_modifier_exercice', {'id' : exerciceData.id}) + '"><i class="icon-edit"></i></a>'+				
													'<a rel="tooltip" data-title="Dupliquer l\'exercice" class="btn btn-default" href="' + Routing.generate('majordesk_app_dupliquer_exercice', {'id' : exerciceData.id}) + '"><i class="icon-copy"></i></a>'+				
													'<button rel="tooltip" data-title="Supprimer l\'exercice" class="btn btn-default remove-exercice"><i class="icon-trash"></i></button>'+			  
													'<button rel="tooltip" data-title="Mettre l\'exercice en ligne" class="btn btn-default upload-exercice"><i class="icon-cloud-upload"></i></button>'+			  
												'</div>'+
											'</div>'+
										  '</div>';
					$('#badge-en-edition').closest('div').after(exercicePreview);
					$('#badge-en-edition').html( parseInt($('#badge-en-edition').html()) + 1);
					$('[rel=fix-tooltip]').tooltip('hide');
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}
		else {
			thisSelector.html('<i class="icon-cloud-download"></i>');
		}
	});	
})