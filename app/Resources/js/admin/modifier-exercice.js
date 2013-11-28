/* JSG Global vars
 * ============== */
 
var jsg_adding_point = 0;
var jsg_adding_segment = 0;
var jsg_adding_droite = 0;
var jsg_segment_x = 0;
var jsg_segment_y = 0;
var jsg_adding_text = 0;
var jsg_adding_tutotext = 0;
var jsgArray = new Array();
if ($('div[id^="box_"]').length > 0) {
	var jsg_count = 0;
	$('div[id^="box_"]').each( function() {
		var jsgMatches = $(this).next('input[type="hidden"]').val().match(/jsg_(\d+)/g);
		if (jsgMatches != null) {
			for (var i=0; i<=jsgMatches.length-1; i++) {
				jsg_count = Math.max(parseInt(jsgMatches[i].substring(4)), jsg_count);
			}
		}
	});
}
else {
	var jsg_count = 0;
}

var box_count = 0;
$('div[id^="box_"]').each(function() {
	box_count = Math.max(parseInt($(this).attr('id').substring(4)), box_count);
});

/* Populating chapitres
 * ==================== */

$('#exerciceformulairetype_mod_exercice_programme').change( function() {
	var id_matiere = $('#exerciceformulairetype_mod_exercice_matiere').val();
	var id_programme = $('#exerciceformulairetype_mod_exercice_programme').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
		success: function(data){
			$('#exerciceformulairetype_mod_exercice_chapitre').html(data.html);
			$('#exerciceformulairetype_mod_exercice_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
		},
		error: function() {
			alert('Sélectionner une matière');
		}
	});
});

$('#exerciceformulairetype_mod_exercice_matiere').change( function() {
	var id_matiere = $('#exerciceformulairetype_mod_exercice_matiere').val();
	var id_programme = $('#exerciceformulairetype_mod_exercice_programme').val();
	if (id_programme !== null) {
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
			success: function(data){
				$('#exerciceformulairetype_mod_exercice_chapitre').html(data.html);
				$('#exerciceformulairetype_mod_exercice_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	}
});

/* Populating parties
 * ================== */

$('#exerciceformulairetype_mod_exercice_chapitre').change( function() {
	var id_chapitre = $('#exerciceformulairetype_mod_exercice_chapitre').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_parties", {'id_chapitre' : id_chapitre}),
		success: function(data){
			$('#exerciceformulairetype_mod_exercice_partie').html(data.html);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});

/* Assigning tags
 * ============== */

$(document)

// Ajouter un emplacement de tag
.on('click', ".add-tag", function() {
	$(this).addClass('adding-tag position-relative-up-5').removeClass('add-tag');
	$(this).before('<span class="new-tag"><input type="text" class="input-small tag-input tag-typeahead" style="height:14px;font-size:10px;line-height:14px;" data-provide="typeahead" /> <button type="button" rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-sm btn-success validate-tag position-relative-up-5"><i class="icon-ok"></i></button> <button type="button" rel="tooltip" data-title="Supprimer" data-placement="right" class="btn btn-sm btn-danger position-relative-up-5 remove-tag"><i class="icon-remove"></i></button><br></span>');
	$('.tag-typeahead').typeahead({
		source: function (query, process) {
			return $.get(Routing.generate("majordesk_app_get_all_tags"), { query: query }, function (data) {
				return process(data.source);
			});
		}
	});
})

// Ajouter et valider un tag
.on('click', ".validate-tag", function() {
	var thisSelector = $(this);
	var nom_tag = thisSelector.prevAll('input').val();
	var id_mod_reponse = thisSelector.closest('tr').attr('data-id-reponse');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_assign_tag", {'nom_tag' : nom_tag, 'id_mod_reponse' : id_mod_reponse}),
		success: function(){
			thisSelector.parent().after('<span class="existing-tag"><span class="label">'+nom_tag+'</span> <button type="button" rel="tooltip" data-title="Supprimer" data-placement="right" class="btn btn-sm btn-danger remove-tag"><i class="icon-remove"></i></button><br></span>').remove();
			$('.adding-tag').removeClass('adding-tag position-relative-up-5').addClass('add-tag');
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
})

// Supprimer un tag non encore ajouté (input encore présent)
.on('click', ".new-tag .remove-tag", function() {
	var thisSelector = $(this).parent();
	thisSelector.fadeOut(400, function() { $(this).remove(); });
	$('.adding-tag').removeClass('adding-tag position-relative-up-5').addClass('add-tag');
})

// Supprimer un tag déjà ajouté
.on('click', ".existing-tag .remove-tag", function() {
	var thisSelector = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer ce tag ?", 'Non', 'Oui', function(result) {
		if (result) {
			var nom_tag = thisSelector.closest('span.existing-tag').children('span.label').first().text();
			var id_mod_reponse = thisSelector.closest('tr').attr('data-id-reponse');
		
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_unassign_tag", {'nom_tag' : nom_tag, 'id_mod_reponse' : id_mod_reponse}),
				success: function(){
					thisSelector.parent().fadeOut(400, function() { $(this).remove(); }); 
				},
				error: function() {
					alert('La requête n\'a pas abouti : le plus probable est que le tag n\'existe pas !');
				}
			});
		}	
	});	
})


/* Textarea autosize
 * ================= */

$('textarea').autosize();   

/* Interaction boutons
 * =================== */

$(document)

// Affichage boutons
.on( { mouseenter: function() { $(this).find('button.interaction-btn').removeClass('invisible'); }, mouseleave: function() { $(this).find('button.interaction-btn').addClass('invisible'); } } , 'div.element')

.on( { mouseenter: function() { $(this).find('.macro-interaction-btn').removeClass('invisible'); }, mouseleave: function() { $(this).find('.macro-interaction-btn').addClass('invisible'); } } , 'div.macro')

.on( { mouseenter: function() { $(this).find('button.question-interaction-btn').removeClass('invisible'); }, mouseleave: function() { $(this).find('button.question-interaction-btn').addClass('invisible'); } } , 'div.question')

// Supprimer un élément
.on('click', ".remove-element", function() {
	var thisSelector = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cet élément ?", 'Non', 'Oui', function(result) {
		if (result) { removeElement(thisSelector); }	
	});	
})

// Supprimer un Macro
.on('click', ".remove-macro", function() {
	var thisSelector = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer ce Macro-élément ?<br>(Attention, tous les éléments qu'il contient seront supprimés)", 'Non', 'Oui', function(result) {
		if (result) { removeMacro(thisSelector); }	
	});	
})

// Supprimer un élément avec réponse
.on('click', ".remove-element-and-reponse", function() {
	var thisSelector = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cet élément ? <br>(Attention, les réponses associées seront supprimées)", 'Non', 'Oui', function(result) {
		if (result) { removeElementAndReponse(thisSelector); }	
	});
})

// Supprimer une réponse
.on('click', ".remove-reponse", function() {
	removeReponse($(this));
})

// Ajouter une réponse
.on('click', ".add-reponse", function() {
	addReponse($(this));
})

// =========================== //

// Ajouter un élément de texte
.on('click', ".add-text", function() {
	addElement($(this), 'text');
})

// Ajouter une formule de maths
.on('click', ".add-maths", function() {
	addElement($(this), 'maths');
})

// Ajouter un retour à la ligne
.on('click', ".add-br", function() {
	addElement($(this), 'br');
})

// =========================== //

// Ajout en cours d'une liste déroulante
.on('click', ".add-liste-deroulante", function() {
	$(this).addClass('adding-liste-deroulante');
})
.on('click', ".cancel-liste-deroulante", function() {
	$('.adding-liste-deroulante').removeClass('adding-liste-deroulante');
})

// Ajouter une liste déroulante
.on('click', ".add-liste-der", function() {
	addElementAndReponse($('.adding-liste-deroulante'), 'liste_der', 'liste_der', '');
	$('.adding-liste-deroulante').removeClass('adding-liste-deroulante');
})

// Ajouter une liste déroulante
.on('click', ".add-liste-der-signe", function() {
	addElementAndReponse($('.adding-liste-deroulante'), 'liste_der', 'liste_der', '+ ## -');
	$('.adding-liste-deroulante').removeClass('adding-liste-deroulante');
})

// Ajouter une liste déroulante
.on('click', ".add-liste-der-croissance", function() {
	addElementAndReponse($('.adding-liste-deroulante'), 'liste_der', 'liste_der', '↗ ## ↘');
	$('.adding-liste-deroulante').removeClass('adding-liste-deroulante');
})

// Ajouter une liste déroulante
.on('click', ".add-liste-der-zero", function() {
	addElementAndReponse($('.adding-liste-deroulante'), 'liste_der', 'liste_der', ' ## || ## 0');
	$('.adding-liste-deroulante').removeClass('adding-liste-deroulante');
})

// =========================== //

// Ajout un figure/graph
.on('click', ".add-figure-graphe", function() {
	$(this).addClass('adding-figure-graphe');
})
.on('click', ".cancel-figure-graph", function() {
	$('.adding-figure-graphe').removeClass('adding-figure-graphe');
})

// Ajouter une figure
.on('click', ".add-jsgbox-figure", function() {
	box_count++;
	addElement($('.adding-figure-graphe'), 'jsgbox', 'box_'+box_count );
	var script = document.createElement( 'script' );
	script.type = 'text/javascript';
	script.text = 'boards["box_'+box_count+'"] = JXG.JSXGraph.initBoard("box_'+box_count+'",{boundingbox : [0,300,570,0], axis:false, showCopyright:false, showNavigation: false});';
	$('#box_'+box_count).next('input[type="hidden"]').val('boards["box_'+box_count+'"] = JXG.JSXGraph.initBoard("box_'+box_count+'",{boundingbox : [0,300,570,0], axis:false, showCopyright:false, showNavigation: false});');
	$('body').append(script);
	$('.adding-figure-graphe').removeClass('adding-figure-graphe');
})

// Ajouter un graphe
.on('click', ".add-jsgbox-graphe", function() {
	var currentBoard = $('.adding-figure-graphe');
	bootbox.prompt("Fenêtre (format: x<sub>min</sub>;x<sub>max</sub>;y<sub>min</sub>;y<sub>max</sub>) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {
			var fenetre = result.split(';');
			if (fenetre.length == 4) {
				box_count++;
				addElement(currentBoard, 'jsggraph', 'box_'+box_count );
				var script = document.createElement( 'script' );
				script.type = 'text/javascript';
				script.text = 'boards["box_'+box_count+'"] = JXG.JSXGraph.initBoard("box_'+box_count+'",{boundingbox : ['+fenetre[0]+','+fenetre[3]+','+fenetre[1]+','+fenetre[2]+'], axis:true, axis:{ticks:{drawLabels:false}}, showCopyright:false, showNavigation: false});';
				$('#box_'+box_count).next('input[type="hidden"]').val('boards["box_'+box_count+'"] = JXG.JSXGraph.initBoard("box_'+box_count+'",{boundingbox : ['+fenetre[0]+','+fenetre[3]+','+fenetre[1]+','+fenetre[2]+'], axis:true, axis:{ticks:{drawLabels:false}}, showCopyright:false, showNavigation: false});');
				$('body').append(script);
			}
			else {
				bootbox.alert("Les coordonnées ne sont pas au bon format. Le format est :<br><br><strong>x<sub>min</sub> <span class='text-red'>;</span> x<sub>max</sub> <span class='text-red'>;</span> y<sub>min</sub> <span class='text-red'>;</span> y<sub>max</sub> </strong>", function() {
				});
			}
		}
	});
	$('.adding-figure-graphe').removeClass('adding-figure-graphe');
})

// Ajouter un point par click
.on('click', ".add-jsg-point-by-click", function() {
	$(this).closest('div.btn-group').nextAll('.jsgcontainer').addClass('cursor-crosshair');
	jsg_adding_point = 1;
})

// Ajouter un point par coordonnées
.on('click', ".add-jsg-point-by-coords", function() {
	var currentBoard = $(this);
	bootbox.prompt("Coordonnées du point (format: nom;x;y) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {
			var coords = result.split(';');
			if (coords.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("point", [coords[1],coords[2]], {size:1, name:coords[0].toUpperCase(), highlight: false, showInfobox: false, fixed:true});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("point", ['+coords[1]+','+coords[2]+'], {size:1, name:"'+coords[0].toUpperCase()+'", highlight: false, showInfobox: false, fixed:true});');
			}
			else {
				bootbox.alert("Les coordonnées entrées ne sont pas sous le bon format. Le format est :<br><br><strong>nom <span class='text-red'>;</span> coordonnée selon x <span class='text-red'>;</span> coordonnée selon y</strong><br><br>Remarque : gauche bas (0;0), gauche haut (0;300), droite bas (570;0)", function() {
				});
			}
		}
	});
})

// Ajouter un segment par click
.on('click', ".add-jsg-segment-by-click", function() {
	$(this).closest('div.btn-group').nextAll('.jsgcontainer').addClass('cursor-help');
	jsg_adding_segment = 1;
})

// Ajouter un segment par points
.on('click', ".add-jsg-segment-by-points", function() {
	var currentBoard = $(this);
	bootbox.prompt("Noms des points (format: nom;nom[;couleur][;pointillés]) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var points = result.split(';');
			var couleurs = { 'rouge': '#ff0000', 'vert':'#08da12', 'noir':'#000000', 'jaune':'#f6e324', 'bleu':'#0000ff', 'bleuclair':'#09f2ed' };
			if (points.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [points[0].toUpperCase(),points[1].toUpperCase()], {straightFirst:false, straightLast:false, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {straightFirst:false, straightLast:false, highlight: false});');
			}
			else if (points.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [points[0].toUpperCase(),points[1].toUpperCase()], {strokeColor: couleurs[points[2]],straightFirst:false, straightLast:false, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {strokeColor: "'+couleurs[points[2]]+'",straightFirst:false, straightLast:false, highlight: false});');
			}
			else if (points.length == 4) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [points[0].toUpperCase(),points[1].toUpperCase()], {strokeColor: couleurs[points[2]],straightFirst:false, straightLast:false, highlight: false, dash: 2});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {strokeColor: "'+couleurs[points[2]]+'",straightFirst:false, straightLast:false, highlight: false, dash: 2});');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Ajouter un segment par ses coordonnées
.on('click', ".add-jsg-segment-by-coords", function() {
	var currentBoard = $(this);
	bootbox.prompt("Segment (format: x<sub>A</sub>;y<sub>A</sub>;x<sub>B</sub>;y<sub>B</sub>[;couleur][;pointillés]) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var coords = result.split(';');
			var couleurs = { 'rouge': '#ff0000', 'vert':'#08da12', 'noir':'#000000', 'jaune':'#f6e324', 'bleu':'#0000ff', 'bleuclair':'#09f2ed' };
			if (coords.length == 4) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[coords[0],coords[1]],[coords[2],coords[3]]], {straightFirst:false, straightLast:false, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+coords[0]+','+coords[1]+'],['+coords[2]+','+coords[3]+']], {straightFirst:false, straightLast:false, highlight: false});');
			}
			else if (coords.length == 5) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[coords[0],coords[1]],[coords[2],coords[3]]], {strokeColor: couleurs[coords[4]],straightFirst:false, straightLast:false, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+coords[0]+','+coords[1]+'],['+coords[2]+','+coords[3]+']], {strokeColor: "'+couleurs[coords[4]]+'",straightFirst:false, straightLast:false, highlight: false});');
			}
			else if (coords.length == 6) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[coords[0],coords[1]],[coords[2],coords[3]]], {strokeColor: couleurs[coords[4]],straightFirst:false, straightLast:false, highlight: false, dash: 2});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+coords[0]+','+coords[1]+'],['+coords[2]+','+coords[3]+']], {strokeColor: "'+couleurs[coords[4]]+'",straightFirst:false, straightLast:false, highlight: false, dash: 2});');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Ajouter une droite par click
.on('click', ".add-jsg-droite-by-click", function() {
	$(this).closest('div.btn-group').nextAll('.jsgcontainer').addClass('cursor-help');
	jsg_adding_droite = 1;
})

// Ajouter une droite par points
.on('click', ".add-jsg-droite-by-points", function() {
	var currentBoard = $(this);
	bootbox.prompt("Noms des points (format: nom;nom) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var points = result.split(';');
			if (points.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [points[0].toUpperCase(),points[1].toUpperCase()], {highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {highlight: false});');
				$(this).removeClass('cursor-help');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Ajouter un droite par ses coordonnées
.on('click', ".add-jsg-droite-by-coords", function() {
	var currentBoard = $(this);
	bootbox.prompt("Droite (format: x<sub>A</sub>;y<sub>A</sub>;x<sub>B</sub>;y<sub>B</sub>) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var coords = result.split(';');
			if (coords.length == 4) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[coords[0],coords[1]],[coords[2],coords[3]]], {highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+coords[0]+','+coords[1]+'],['+coords[2]+','+coords[3]+']], {highlight: false});');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Ajouter un vecteur par points
.on('click', ".add-jsg-vecteur-by-points", function() {
	var currentBoard = $(this);
	bootbox.prompt("Noms des points (format: nom;nom) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var points = result.split(';');
			if (points.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("arrow", [points[0].toUpperCase(),points[1].toUpperCase()], {strokeWidth:1, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("arrow", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {strokeWidth:1, highlight: false});');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Ajouter un vecteur par ses coordonnées
.on('click', ".add-jsg-vecteur-by-coords", function() {
	var currentBoard = $(this);
	bootbox.prompt("Vecteur (format: x<sub>A</sub>;y<sub>A</sub>;x<sub>B</sub>;y<sub>B</sub>) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {      
			var coords = result.split(';');
			if (coords.length == 4) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("arrow", [[coords[0],coords[1]],[coords[2],coords[3]]], {strokeWidth:1, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("arrow", [['+coords[0]+','+coords[1]+'],['+coords[2]+','+coords[3]+']], {strokeWidth:1, highlight: false});');
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})




// Ajouter un cercle par le centre et 1 point
.on('click', ".add-jsg-circle-by-centre-point", function() {
	var currentBoard = $(this);
	bootbox.prompt("Format : centre;point ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var points = result.split(';');
			if (points.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("circle", [points[0].toUpperCase(),points[1].toUpperCase()], {highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("circle", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'"], {highlight: false});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou le centre n'existe pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter un cercle par le centre et le rayon
.on('click', ".add-jsg-circle-by-centre-rayon", function() {
	var currentBoard = $(this);
	bootbox.prompt("Format : centre;rayon ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var points = result.split(';');
			if (points.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("circle", [points[0].toUpperCase(),points[1]], {highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("circle", ["'+points[0].toUpperCase()+'",'+points[1]+'], {highlight: false});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou le centre n'existe pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter un cercle par 3 points
.on('click', ".add-jsg-circle-by-3points", function() {
	var currentBoard = $(this);
	bootbox.prompt("Noms des 3 points formant le cercle (format: nom;nom;nom) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var points = result.split(';');
			if (points.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("circle", [points[0].toUpperCase(),points[1].toUpperCase(),points[2].toUpperCase()], {highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("circle", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'","'+points[2].toUpperCase()+'"], {highlight: false});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou certains points n'existent pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter un angle
.on('click', ".add-jsg-angle", function() {
	var currentBoard = $(this);
	bootbox.prompt("Noms des 3 points formant l'angle (format: nom;nom;nom) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var points = result.split(';');
			if (points.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("angle", [points[0].toUpperCase(),points[1].toUpperCase(),points[2].toUpperCase()], {withLabel: false, radius:20, type:"sector", orthoType:"square"});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("angle", ["'+points[0].toUpperCase()+'","'+points[1].toUpperCase()+'","'+points[2].toUpperCase()+'"], {withLabel: false, radius:20, type:"sector", orthoType:"square"});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou certains points n'existent pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter une fonction
.on('click', ".add-jsg-fonction", function() {
	var currentBoard = $(this);
	bootbox.prompt("Fonction (format: nom;fonction;[couleur;][x<sub>min</sub>;x<sub>max</sub>]) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var props = result.split(';');
			var couleurs = { 'rouge': '#ff0000', 'vert':'#08da12', 'noir':'#000000', 'jaune':'#f6e324', 'bleu':'#0000ff', 'bleuclair':'#09f2ed' };
			if (props.length == 2) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("functiongraph", [function(x){return eval(props[1])}], {name:props[0].toUpperCase(), highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("functiongraph", [function(x){return '+props[1]+'}], {name:"'+props[0].toUpperCase()+'", highlight: false});');
			}
			else if (props.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("functiongraph", [function(x){return eval(props[1])}], {strokeColor : couleurs[props[2]], name:props[0].toUpperCase(), highlight: false} );
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("functiongraph", [function(x){return '+props[1]+'}], {strokeColor : "'+couleurs[props[2]]+'", name:"'+props[0].toUpperCase()+'", highlight: false});');
			}
			else if (props.length == 5) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("functiongraph", [function(x){return eval(props[1])}, props[3], props[4]], {strokeColor : couleurs[props[2]], name:props[0].toUpperCase(), highlight: false} );
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("functiongraph", [function(x){return '+props[1]+'}, '+props[3]+', '+props[4]+'], {strokeColor : "'+couleurs[props[2]]+'", name:"'+props[0].toUpperCase()+'", highlight: false});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou certains points n'existent pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter une intégrale
.on('click', ".add-jsg-integrale", function() {
	var currentBoard = $(this);
	bootbox.prompt("Fonction (format: fonction;x<sub>min</sub>;x<sub>max</sub>[;couleur]) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) {                                        
			var props = result.split(';');
			var couleurs = { 'rouge': '#ff0000', 'vert':'#08da12', 'noir':'#000000', 'jaune':'#f6e324', 'bleu':'#0000ff', 'bleuclair':'#09f2ed' };
			if (props.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("integral", [[props[1],props[2]],props[0].toUpperCase()], {fillOpacity: 0.3, label: {fontSize:0}, curveLeft: {visible: false}, curveRight: {visible: false}, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("integral", [['+props[1]+','+props[2]+'],"'+props[0].toUpperCase()+'"], {fillOpacity: 0.3, label: {fontSize:0}, curveLeft: {visible: false}, curveRight: {visible: false}, highlight: false});');
			}
			else if (props.length == 4) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("integral", [[props[1],props[2]],props[0].toUpperCase()], {fillColor : couleurs[props[3]], fillOpacity: 0.3, label: {fontSize:0}, curveLeft: {visible: false}, curveRight: {visible: false}, highlight: false} );
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("integral", [['+props[1]+','+props[2]+'],"'+props[0].toUpperCase()+'"], {fillColor : "'+couleurs[props[3]]+'", fillOpacity: 0.3, label: {fontSize:0}, curveLeft: {visible: false}, curveRight: {visible: false}, highlight: false});');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect ou la fonction n'existe pas...", function() {
				});
			}                   
		}
	});
})

// Ajouter du texte
// .on('click', ".add-jsg-text", function() {
	// $(this).nextAll('.jsgcontainer').addClass('cursor-text');
	// jsg_adding_text = 1;
// })

// Ajouter du texte dans un tutocours
// .on('click', ".add-jsg-tutotext", function() {
	// $(this).nextAll('.jsgcontainer').addClass('cursor-text');
	// jsg_adding_tutotext = 1;
// })

// Ajouter du texte par ses coordonnées
.on('click', ".add-jsg-text", function() {
	var box = $(this); 
	bootbox.prompt("Texte/Légende (format: x;y;texte) : ", 'Annuler', 'Ok', function(result) {                
		if (result !== null) { 
			var coords = result.split(';');
			if (coords.length == 3) {
				jsg_count++;
				jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [coords[0],coords[1],coords[2]]);
				box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+coords[0]+','+coords[1]+',"'+coords[2]+'"]);');
			}
			else {
				bootbox.alert("Le format utilisé est incorrect. (Attention à ne pas utiliser de point-virgule dans le texte!)", function() {
				});
			} 
		}	
	});
})

// Annuler la dernière modification
.on('click', ".undo-jsg", function() {
	var currentScript = $(this).closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val();
	currentScript = currentScript.slice(0, - 1);
	
	var scriptArray = currentScript.split(';');
	var equalArray = scriptArray[scriptArray.length-1].split('=');
	var varArray = equalArray[0].split(' ');
	var jsgVar = varArray[varArray.length-1];
		var jsgVarNumber = jsgVar.substring(4);
		if (typeof jsgArray[jsgVarNumber] != 'undefined') {
			boards[$(this).closest('div.element').children('div.jsgcontainer').attr('id')].removeObject(jsgArray[jsgVarNumber]);
		}
		else {
			boards[$(this).closest('div.element').children('div.jsgcontainer').attr('id')].removeObject(eval(jsgVar));
		}
	
	newScript = currentScript.slice(0, - scriptArray[scriptArray.length-1].length);
	$(this).closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(newScript)
})

// Ajouter un droite graduée
.on('click', ".add-grad-jsg-1", function() {
	var currentBoard = $(this);
	// bootbox.confirm("Insérer une droite graduée ?", 'Annuler', 'Ok', function(result) {                
		// if (result) {      
			jsg_count++;
			jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[0,240],[570,240]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});
			currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [[0,240],[570,240]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});');
			jsg_count++;
			jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("ticks", [jsgArray[jsg_count-1], 200], {highlight: false, majorHeight: 8, minorHeight: 8});
			currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("ticks", [jsg_'+parseInt(jsg_count-1)+', 200], {highlight: false, majorHeight: 8, minorHeight: 8});');
		// }
	// });
})
.on('click', ".add-grad-jsg-2", function() {
	var currentBoard = $(this);  
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[0,180],[570,180]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [[0,180],[570,180]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});');
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("ticks", [jsgArray[jsg_count-1], 200], {highlight: false, majorHeight: 8, minorHeight: 8});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("ticks", [jsg_'+parseInt(jsg_count-1)+', 200], {highlight: false, majorHeight: 8, minorHeight: 8});');
})
.on('click', ".add-grad-jsg-3", function() {
	var currentBoard = $(this);  
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[0,120],[570,120]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [[0,120],[570,120]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});');
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("ticks", [jsgArray[jsg_count-1], 200], {highlight: false, majorHeight: 8, minorHeight: 8});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("ticks", [jsg_'+parseInt(jsg_count-1)+', 200], {highlight: false, majorHeight: 8, minorHeight: 8});');
})
.on('click', ".add-grad-jsg-4", function() {
	var currentBoard = $(this);  
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[0,60],[570,60]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [[0,60],[570,60]], {highlight: false, strokeColor: "#000000", strokeWidth: 1});');
	jsg_count++;
	jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("ticks", [jsgArray[jsg_count-1], 200], {highlight: false, majorHeight: 8, minorHeight: 8});
	currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("ticks", [jsg_'+parseInt(jsg_count-1)+', 200], {highlight: false, majorHeight: 8, minorHeight: 8});');
})

// Ajouter un segment ouvert fermé
.on('click', ".add-segment-ouv-fer", function() {
	var currentBoard = $(this);
	bootbox.prompt("Intervalle (format: x<sub>A</sub>;y<sub>A</sub>;']/0/['(ouv/rien/fer);<br>x<sub>B</sub>;y<sub>B</sub>;']/0/['(fer/rien/ouv)<br>[;couleur])<br>tick1:40, tick2:80,... segment1:240, segment2:180, ... ", 'Annuler', 'Ok', function(result) {                          
		if (result !== null) {      
			var coords = result.split(';');
			var couleurs = { 'rouge': '#ff0000', 'vert':'#08da12', 'noir':'#000000', 'jaune':'#f6e324', 'bleu':'#0000ff', 'bleuclair':'#09f2ed' };
			if (coords.length != 7) {
				var couleur = '#ff0000';
			}
			else {
				var couleur = couleurs[coords[6]]
			}

			if (coords.length >= 6) {
				jsg_count++;
				jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[coords[0],coords[1]],[coords[3],coords[4]]], {strokeColor: couleur,straightFirst:false, straightLast:false, highlight: false});
				currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+coords[0]+','+coords[1]+'],['+coords[3]+','+coords[4]+']], {strokeColor: "'+couleur+'",straightFirst:false, straightLast:false, highlight: false});');
				if (coords[2] == ']') {
					jsg_count++;
					jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [parseInt(coords[0]-4),coords[1],'<strong>]</strong>'], {highlight: false, fontSize: 20, strokeColor: couleur});
					currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+parseInt(coords[0]-4)+','+coords[1]+',"<strong>]</strong>"], {highlight: false, fontSize: 20, strokeColor: "'+couleur+'"});');
				}
				else if (coords[2] == '[') {
					jsg_count++;
					jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [parseInt(coords[0]-2),coords[1],'<strong>[</strong>'], {highlight: false, fontSize: 20, strokeColor: couleur});
					currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+parseInt(coords[0]-2)+','+coords[1]+',"<strong>[</strong>"], {highlight: false, fontSize: 20, strokeColor: "'+couleur+'"});');
				}
				if (coords[5] == ']') {
					jsg_count++;
					jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [parseInt(coords[3]-3),coords[4],'<strong>]</strong>'], {highlight: false, fontSize: 20, strokeColor: couleur});
					currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+parseInt(coords[3]-3)+','+coords[4]+',"<strong>]</strong>"], {highlight: false, fontSize: 20, strokeColor: "'+couleur+'"});');
				}
				else if (coords[5] == '[') {
					jsg_count++;
					jsgArray[jsg_count] = boards[currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [parseInt(coords[3]-2),coords[4],'<strong>[</strong>'], {highlight: false, fontSize: 20, strokeColor: couleur});
					currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(currentBoard.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+currentBoard.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+parseInt(coords[3]-2)+','+coords[4]+',"<strong>[</strong>"], {highlight: false, fontSize: 20, strokeColor: "'+couleur+'"});');
				}
			}
			else {
				bootbox.alert("Les points entrés ne semblent pas exister...", function() {
				});
			}
		}
	});
})

// Intéraction box
.on('click', 'div[id^="box_"]', function(e) {
	if (jsg_adding_point == 1) {
		var box = $(this);
		var parentOffset = box.parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = 322-(e.pageY - parentOffset.top);
		bootbox.prompt("Nom du point : ", 'Annuler', 'Ok', function(result) {                
			if (result !== null) {                                             
				jsg_count++;
				jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("point", [relX,relY], {size:1, name:result.toUpperCase(), highlight: false, showInfobox: false, fixed:true});
				box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("point", ['+relX+','+relY+'], {size:1, name:"'+result.toUpperCase()+'", highlight: false, showInfobox: false, fixed:true});');
				box.removeClass('cursor-crosshair');                    
			}
		});
		jsg_adding_point = 0;  
	}
	else if (jsg_adding_segment == 1) {
		var parentOffset = $(this).parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = 322-(e.pageY - parentOffset.top);
		jsg_segment_x = relX;
		jsg_segment_y = relY;
		jsg_adding_segment = 2;
	}
	else if (jsg_adding_segment == 2) {
		var box = $(this);
		var parentOffset = box.parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = 322-(e.pageY - parentOffset.top);
		jsg_count++;
		jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[jsg_segment_x,jsg_segment_y],[relX,relY]], {straightFirst:false, straightLast:false, highlight: false});
		box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+jsg_segment_x+', '+jsg_segment_y+'],['+relX+','+relY+']], {straightFirst:false, straightLast:false, highlight: false});');
		box.removeClass('cursor-help');
		jsg_adding_segment = 0;
	}
	else if (jsg_adding_droite == 1) {
		var parentOffset = $(this).parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = 322-(e.pageY - parentOffset.top);
		jsg_segment_x = relX;
		jsg_segment_y = relY;
		jsg_adding_droite = 2;
	}
	else if (jsg_adding_droite == 2) {
		var box = $(this);
		var parentOffset = box.parent().offset(); 
		var relX = e.pageX - parentOffset.left;
		var relY = 322-(e.pageY - parentOffset.top);
		jsg_count++;
		jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("line", [[jsg_segment_x,jsg_segment_y],[relX,relY]], {highlight: false});
		box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("line", [['+jsg_segment_x+', '+jsg_segment_y+'],['+relX+','+relY+']], {highlight: false});');
		box.removeClass('cursor-help');
		jsg_adding_droite = 0;
	}
	// else if (jsg_adding_text == 1) {
		// var box = $(this);
		// var parentOffset = box.parent().offset(); 
		// var relX = e.pageX - parentOffset.left;
		// var relY = 322-(e.pageY - parentOffset.top);
		// bootbox.prompt("Texte/Légende : ", 'Annuler', 'Ok', function(result) {                
			// if (result !== null) {                                             
				// jsg_count++;
				// jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [relX,relY,result]);
				// box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+relX+','+relY+',"'+result+'"]);');
				// box.removeClass('cursor-text');                    
			// }
		// });
		// jsg_adding_text = 0;  
	// }
	// else if (jsg_adding_tutotext == 1) {
		// var box = $(this);
		// var parentOffset = box.parent().offset(); 
		// var relX = 570/954*(e.pageX - parentOffset.left);
		// var relY = 322-(e.pageY - parentOffset.top);
		// bootbox.prompt("Texte/Légende : ", 'Annuler', 'Ok', function(result) {                
			// if (result !== null) {                                             
				// jsg_count++;
				// jsgArray[jsg_count] = boards[box.closest('div.element').children('div.jsgcontainer').attr('id')].create("text", [relX,relY,result]);
				// box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val(box.closest('div.element').children('div.jsgcontainer').next('input[type="hidden"]').val()+' var jsg_'+jsg_count+'=boards["'+box.closest('div.element').children('div.jsgcontainer').attr('id')+'"].create("text", ['+relX+','+relY+',"'+result+'"]);');
				// box.removeClass('cursor-text');                    
			// }
		// });
		// jsg_adding_tutotext = 0;  
	// }
})

// =========================== //

// Ajout en cours d'une case
.on('click', ".add-case", function() {
	$(this).addClass('adding-case');
})
.on('click', ".cancel-case", function() {
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case simple
.on('click', ".add-case-simple", function() {
	addElementAndReponse($('.adding-case'), 'case', 'simple')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case avancée
.on('click', ".add-case-avancee", function() {
	addElementAndReponse($('.adding-case'), 'case', 'avancee')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case point
.on('click', ".add-case-point", function() {
	addElementAndReponse($('.adding-case'), 'case', 'point')
	$('.adding-case').removeClass('adding-case');
})

// =========================== //

// Ajout en cours d'une case puissance
.on('click', ".add-case-puissance", function() {
	$(this).addClass('adding-case');
})
.on('click', ".cancel-case-puissance", function() {
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case puissance simple
.on('click', ".add-case-puissance-simple", function() {
	addElementAndReponse($('.adding-case'), 'case puissance', 'simple')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case puissance avancée
.on('click', ".add-case-puissance-avancee", function() {
	addElementAndReponse($('.adding-case'), 'case puissance', 'avancee')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case puissance point
.on('click', ".add-case-puissance-point", function() {
	addElementAndReponse($('.adding-case'), 'case puissance', 'point')
	$('.adding-case').removeClass('adding-case');
})

// =========================== //

// Ajout en cours d'une case indice
.on('click', ".add-case-indice", function() {
	$(this).addClass('adding-case');
})
.on('click', ".cancel-case-indice", function() {
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case indice simple
.on('click', ".add-case-indice-simple", function() {
	addElementAndReponse($('.adding-case'), 'case indice', 'simple')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case indice avancée
.on('click', ".add-case-indice-avancee", function() {
	addElementAndReponse($('.adding-case'), 'case indice', 'avancee')
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case indice point
.on('click', ".add-case-indice-point", function() {
	addElementAndReponse($('.adding-case'), 'case indice', 'point')
	$('.adding-case').removeClass('adding-case');
})

// =========================== //

// Ajout en cours d'un radio
.on('click', ".add-radio", function() {
	$(this).addClass('adding-radio');
})
.on('click', ".cancel-radio", function() {
	$('.adding-radio').removeClass('adding-radio');
})

// Ajouter un radio (2 choix)
.on('click', ".add-radio-2", function() {
	console.log($('.adding-radio').length);
	addMacroAndReponse($('.adding-radio'), 'radio', 2);
	$('.adding-radio').removeClass('adding-radio');
})

// Ajouter un radio (3 choix)
.on('click', ".add-radio-3", function() {
	addMacroAndReponse($('.adding-radio'), 'radio', 3);
	$('.adding-radio').removeClass('adding-radio');
})

// Ajouter un radio (4 choix)
.on('click', ".add-radio-4", function() {
	addMacroAndReponse($('.adding-radio'), 'radio', 4);
	$('.adding-radio').removeClass('adding-radio');
})

// Ajouter un radio (5 choix)
.on('click', ".add-radio-5", function() {
	addMacroAndReponse($('.adding-radio'), 'radio', 5);
	$('.adding-radio').removeClass('adding-radio');
})

// Ajouter un radio (6 choix)
.on('click', ".add-radio-6", function() {
	addMacroAndReponse($('.adding-radio'), 'radio', 6);
	$('.adding-radio').removeClass('adding-radio');
})

// =========================== //

// Ajout en cours d'un checkbox
.on('click', ".add-checkbox", function() {
	$(this).addClass('adding-checkbox');
})
.on('click', ".cancel-checkbox", function() {
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (1 choix)
.on('click', ".add-checkbox-1", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 1, 1);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (2 choix)
.on('click', ".add-checkbox-2", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 2, 2);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (3 choix)
.on('click', ".add-checkbox-3", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 3, 3);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (4 choix)
.on('click', ".add-checkbox-4", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 4, 4);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (5 choix)
.on('click', ".add-checkbox-5", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 5, 5);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// Ajouter un checkbox (6 choix)
.on('click', ".add-checkbox-6", function() {
	addMacroAndReponse($('.adding-checkbox'), 'checkbox', 6, 6);
	$('.adding-checkbox').removeClass('adding-checkbox');
})

// =========================== //

// Ajout en cours de vignettes
.on('click', ".add-vignettes", function() {
	$(this).addClass('adding-vignettes');
})
.on('click', ".cancel-vignettes", function() {
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 2 vignettes
.on('click', ".add-vignette-2", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 2, 2);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 3 vignettes
.on('click', ".add-vignette-3", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 3, 3);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 4 vignettes
.on('click', ".add-vignette-4", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 4, 4);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 5 vignettes
.on('click', ".add-vignette-5", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 5, 5);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 6 vignettes
.on('click', ".add-vignette-6", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 6, 6);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 7 vignettes
.on('click', ".add-vignette-7", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 7, 7);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 8 vignettes
.on('click', ".add-vignette-8", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 8, 8);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 9 vignettes
.on('click', ".add-vignette-9", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 9, 9);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 10 vignettes
.on('click', ".add-vignette-10", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 10, 10);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 11 vignettes
.on('click', ".add-vignette-11", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 11, 11);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// Ajouter un groupe de 12 vignettes
.on('click', ".add-vignette-12", function() {
	addMacroAndReponse($('.adding-vignettes'), 'vignettes', 12, 12);
	$('.adding-vignettes').removeClass('adding-vignettes');
})

// =========================== //

// Ajouter un ModMacro de type normal
.on('click', ".add-macro-normal", function() {
	addMacro($(this), 'normal')
})	

// =========================== //

// Ajouter un ModMacro de type fraction
.on('click', ".add-macro-fraction", function() {
	addMacro($(this), 'fraction')
})

// =========================== //

// Ajout en cours d'un tableau
.on('click', ".add-tableau", function() {
	$(this).addClass('adding-tableau');
})
.on('click', ".cancel-tableau", function() {
	$('.adding-tableau').removeClass('adding-tableau');
})

// Ajouter un ModMacro de type tableau
.on('click', ".add-selected-tableau", function() {
	addMacro($('.adding-tableau'), 'tableau', $('#add-tableau-lignes').val(), $('#add-tableau-colonnes').val())
	$('.adding-tableau').removeClass('adding-tableau');
})

// =========================== //

// Ajout en cours d'un tableau de signe
.on('click', ".add-tableau-analyse", function() {
	$(this).addClass('adding-tableau-analyse');
})
.on('click', ".cancel-tableau-analyse", function() {
	$('.adding-tableau-analyse').removeClass('adding-tableau-analyse');
})

// Ajouter un ModMacro de type tableau de signe
.on('click', ".add-selected-tableau-analyse", function() {
	var nb_lignes = 5*(parseInt($('#add-tableau-analyse-lignes').val())-1)+1;
	var nb_colonnes = 2*parseInt($('#add-tableau-analyse-valeurs').val())+2;
	addMacro($('.adding-tableau-analyse'), 'tableau analyse', nb_lignes, nb_colonnes);
	$('.adding-tableau-analyse').removeClass('adding-tableau-analyse');
})

// =========================== //

// Ajouter un ModMacro de type intégrale
.on('click', ".add-macro-integrale", function() {
	addMacro($(this), 'integrale')
})

// =========================== //

// Ajouter une question
.on('click', ".add-question", function() {
	addQuestion($(this));
})

// Supprimer une question
.on('click', ".remove-question", function() {
	var $this = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette question ?", 'Non', 'Oui', function(result) {			
		if (result) { 
			removeQuestion($this);
		}
	});
})

// =========================== //

// Cocher/Décocher une réponse
.on('click', ".dependance-checkbox", function() {
	tickReponse($(this));
})

/* Functions
 * ========= */

function addQuestion(thisSelector) {
	var existingQuestion= new Question( thisSelector.closest('div.question') );
	var newQuestion     = new NewQuestion( parseInt(existingQuestion.numero) + 1 );
	
	existingQuestion.incrementQuestions();
	existingQuestion.selector.next().after(newQuestion.content);
}

function removeQuestion(thisSelector) {
	var existingQuestion= new Question( thisSelector.closest('div.question') );
	
	existingQuestion.decrementQuestions();
	existingQuestion.selector.fadeOut(400, function() { $(this).next().remove(); $(this).remove(); });
}

function addElement(thisSelector, type, clavier, graph_type) {
	clavier = typeof clavier !== 'undefined' ? clavier : '';
	var question        = new Question( thisSelector.closest('div.question') );
	var macro           = new Macro( question, thisSelector.closest('div.macro') );
	var existingElement = new Element( question, macro, thisSelector.closest('div.element') );
	if (existingElement.selector.attr('data-numero-vignette')) {
		var newElement      = new NewElement( question, macro, type, existingElement.numero + 1, existingElement.selector.attr('data-numero-vignette'), clavier, '' );
	}
	else {
		var newElement      = new NewElement( question, macro, type, existingElement.numero + 1, 0, clavier, '' );
	}
	
	existingElement.incrementSiblings();
	existingElement.selector.after(newElement.content);
}

function removeElement(thisSelector) {
	var question        = new Question( thisSelector.closest('div.question') );
	var macro           = new Macro( question, thisSelector.closest('div.macro') );
	var existingElement = new Element( question, macro, thisSelector.closest('div.element') );
	
	existingElement.decrementSiblings();
	if ( existingElement.selector.hasClass('jsgbox') || existingElement.selector.hasClass('jsggraph')) {
		delete boards[thisSelector.siblings('div[id^="box_"]').first().attr('id')];
	}
	existingElement.selector.fadeOut(400, function() { $(this).remove(); });
}

function removeMacro(thisSelector) {
	var question        = new Question( thisSelector.closest('div.question') );
	var existingMacro   = new Macro( question, thisSelector.closest('div.macro') );

	var mapping         = new Mapping( question, question.getCurrentLoopMapping() );

	question.selector.next().find('tr[data-numero-reponse]').each( function() {
		if ($(this).attr('data-numero-reponse') > existingMacro.getPrecedingInputsNumber() && $(this).attr('data-numero-reponse') < existingMacro.getPrecedingInputsNumber() + existingMacro.getInputsNumber() + 1) {
			var existingReponse = new ExistingReponse( question, mapping, $(this) );	
			question.decrementAllReponseAbove(existingReponse.numero);
			existingReponse.reorganizeCheckReponseBeforeDeletion();
			existingReponse.selector.remove();	
		}
	});			
	
	existingMacro.decrementMacros();
	existingMacro.selector.fadeOut(400, function() { $(this).remove(); });
}

function addElementAndReponse(thisSelector, type, clavier, liste_der_content) {
	var question        = new Question( thisSelector.closest('div.question') );
	var macro           = new Macro( question, thisSelector.closest('div.macro') );
	var existingElement = new Element( question, macro, thisSelector.closest('div.element') );
	if (existingElement.selector.attr('data-numero-vignette')) {
		var newElement      = new NewElement( question, macro, type, existingElement.numero + 1, existingElement.selector.attr('data-numero-vignette'), clavier );
	}
	else {
		var newElement      = new NewElement( question, macro, type, existingElement.numero + 1, 0, clavier, liste_der_content );
	}
	
	existingElement.incrementSiblings();
	existingElement.selector.after(newElement.content);
	
	var precedingInputs = existingElement.getPrecedingInputsNumber();
	var mapping         = new Mapping( question, question.getCurrentLoopMapping() );
	var newReponse      = new NewReponse(question, mapping, precedingInputs + 1, 0, question.getCurrentDependances(), clavier);
	
	question.incrementAllReponseAbove(precedingInputs);
	if ( question.getReponseSelectorByNumero(precedingInputs).length != 0 )
	{		
		question.getReponseSelectorByNumero(precedingInputs).after(newReponse.content);
	}
	else
	{
		question.getTbodySelector().prepend(newReponse.content);
	}
}

function removeElementAndReponse(thisSelector) {
	var question        = new Question( thisSelector.closest('div.question') );
	var macro           = new Macro( question, thisSelector.closest('div.macro') );
	var existingElement = new Element( question, macro, thisSelector.closest('div.element') );
	
	existingElement.decrementSiblings();
	existingElement.selector.fadeOut(400, function() { $(this).remove(); });
	
	var mapping         = new Mapping( question, question.getCurrentLoopMapping() );

	question.selector.next().find('tr[data-numero-reponse="' + existingElement.getPrecedingInputsNumber() + '"]').each( function() {
		var existingReponse = new ExistingReponse( question, mapping, $(this) );		
		existingReponse.reorganizeCheckReponseBeforeDeletion();
		existingReponse.selector.remove();
	});
	
	question.decrementAllReponseAbove(existingElement.getPrecedingInputsNumber());
}

function removeReponse(thisSelector) {
	var question        = new Question( thisSelector.closest('div.reponse').prev() );
	var mapping         = new Mapping( question, thisSelector.closest('tr').attr('data-loop-mapping') );
	var existingReponse = new ExistingReponse( question, mapping, thisSelector.closest('tr') );

	if (existingReponse.getNumeroReponseCount() > 1) {
		bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette réponse ?", 'Non', 'Oui', function(result) {			
			if (result) { 
				existingReponse.reorganizeCheckReponseBeforeDeletion();
				existingReponse.selector.fadeOut(400, function() { $(this).remove(); }); 
			}
		});
	}
	else {
		bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette réponse ? <br>(Attention, l'élément d'input sera supprimé)", 'Non', 'Oui', function(result) {
			if (result) { 
				existingReponse.reorganizeCheckReponseBeforeDeletion();
				removeElementAndReponse(existingReponse.selector.closest('div.reponse').prev().find('.input-eleve').filter(':eq(' + existingReponse.numeroMoins + ')')); 
			}
		});
	}
}

function addReponse(thisSelector) {
	var question        = new Question( thisSelector.closest('div.reponse').prev() );		
	var mapping         = new Mapping( question, thisSelector.closest('tr').attr('data-loop-mapping') );
	var existingReponse = new ExistingReponse( question, mapping, thisSelector.closest('tr') );
	var newReponse      = new NewReponse(question, mapping, existingReponse.numero, existingReponse.getHighestLoopMapping() + 1, question.getCurrentDependances(), existingReponse.clavier);
	
	existingReponse.selector.after(newReponse.content);
}

function tickReponse(thisSelector) {
	var question        = new Question( thisSelector.closest('div.reponse').prev() );
	var mapping         = new Mapping( question, thisSelector.closest('tr').attr('data-loop-mapping') );
	var existingReponse = new ExistingReponse( question, mapping, thisSelector.closest('tr') );
	var checkReponse    = new CheckReponse(existingReponse, thisSelector);

	if ( checkReponse.selector.is(':checked') )
	{
		if ( checkReponse.getCheckedInColumn() >= 2)
		{

			checkReponse.replaceFormInputNamesOnCheck();
		}
		if ( checkReponse.getCheckedInColumn() == 2 && checkReponse.selector.is(':last-child'))
		{
			checkReponse.addColumn();
		}
		checkReponse.disableAltReponsesInColumn();
		checkReponse.disableOtherChecksInLine();
		
	}
	else
	{	
		checkReponse.replaceFormInputNamesOnUncheck();
		checkReponse.enableAltReponsesInColumn();
		checkReponse.enableOtherChecksInLine();
		if ( checkReponse.getCheckedInColumn() == 0 && !thisSelector.is(':last-child') )
		{
			checkReponse.removeColumn();
		}			
	}
}

function addMacro(thisSelector, type, nombre_lignes, nombre_colonnes) {
	var question        = new Question( thisSelector.closest('div.question') );
	var existingMacro   = new Macro( question, thisSelector.closest('div.macro') );
	var newMacro        = new NewMacro( question, type, existingMacro.numero + 1, 0, nombre_lignes, nombre_colonnes, thisSelector.closest('div.macro').attr('data-fond') );
	
	existingMacro.incrementMacros();
	existingMacro.selector.after(newMacro.content);
}

function addMacroAndReponse(thisSelector, type, input_number, reponse_number) {
	reponse_number = typeof reponse_number !== 'undefined' ? reponse_number : 1;
	var question        = new Question( thisSelector.closest('div.question') );
	var existingMacro   = new Macro( question, thisSelector.closest('div.macro') );
	var newMacro        = new NewMacro( question, type, existingMacro.numero + 1, input_number, 0, 0, thisSelector.closest('div.macro').attr('data-fond') );
	
	existingMacro.incrementMacros();
	existingMacro.selector.after(newMacro.content);
	
	var precedingInputs = existingMacro.getPrecedingInputsNumber() + existingMacro.getInputsNumber();

	for (var i=0; i<=reponse_number-1; i++) {
		var mapping         = new Mapping( question, question.getCurrentLoopMapping() );
		if (type == 'radio' || type == 'vignettes') {
			var clavier_type    = type+'-'+input_number;
		}
		else {
			var clavier_type    = type;
		}
		var newReponse      = new NewReponse(question, mapping, precedingInputs + i + 1, 0, question.getCurrentDependances(), clavier_type, input_number);
		
		question.incrementAllReponseAbove(precedingInputs + i);
		if ( question.getReponseSelectorByNumero(precedingInputs + i).length != 0 )
		{		
			question.getReponseSelectorByNumero(precedingInputs + i).after(newReponse.content);
		}
		else
		{
			question.getTbodySelector().prepend(newReponse.content);
		}
	}
}


/* Objects
 * ======= */

function Question(selector) {			
	this.selector = selector,
	this.numero = parseInt(selector.attr('data-numero-question')),
	this.loop = parseInt(this.numero) - 1
	
	this.getCurrentLoopMapping = function() {
		var loopMapping = -1;
		this.selector.next().find('tbody').children().each( function() {
			if ( $(this).attr('data-loop-mapping') !== undefined)
			{
				loopMapping = Math.max(loopMapping, $(this).attr('data-loop-mapping'));
			}
		});
		loopMapping = loopMapping + 1;	
		return loopMapping;
	}
	
	this.getCurrentDependances = function() {
		var dependancesNumber = parseInt(this.selector.next().find('tbody tr:first-child td:last-child').children().length);
		var dependances = '';
		if ( dependancesNumber > 0 )
		{
			for( var i=1; i<=dependancesNumber; i++)
			{			
				dependances = dependances + '<input class="dependance-checkbox" data-check-numero="' + i + '" type="checkbox"> ';
			}
		}
		else
		{
			dependances = '<input class="dependance-checkbox" data-check-numero="1" type="checkbox"> ';
		}
		return dependances;
	}
	
	this.getCurrentNumeroReponse = function() {
		return this.selector.find('div.input-eleve').length;
	}
	
	this.getReponseSelectorByNumero  = function(numero) {
		return this.selector.next().find('tr[data-numero-reponse="' + numero + '"]').last();			
	}
	
	this.getTbodySelector = function() {
		return this.selector.next().find('tbody');
	}
	
	this.incrementAllReponseAbove = function(numero) {
		this.getTbodySelector().find('tr[data-numero-reponse]').each( function() {
			if ( $(this).attr('data-numero-reponse') > numero )
			{
				var newNumeroReponse = parseInt($(this).attr('data-numero-reponse')) + 1;
				$(this).attr('data-numero-reponse', newNumeroReponse);
				$(this).find('td:first-child strong').html(newNumeroReponse);
				$(this).find('td:first-child input').attr('value', newNumeroReponse);
			}
		});
	}
	
	this.decrementAllReponseAbove = function(numero) {
		this.getTbodySelector().find('tr[data-numero-reponse]').each( function() {
			if ( $(this).attr('data-numero-reponse') > numero )
			{
				var newNumeroReponse = parseInt($(this).attr('data-numero-reponse')) - 1;
				$(this).attr('data-numero-reponse', newNumeroReponse);
				$(this).find('td:first-child strong').html(newNumeroReponse);
				$(this).find('td:first-child input').attr('value', newNumeroReponse);
			}
		});
	}
	
	this.incrementQuestions = function() {
		var numero = this.numero;
		this.selector.siblings('[data-numero-question]').each( function() {
			if ( $(this).attr('data-numero-question') > numero ) {
			
				$(this).attr('data-numero-question', parseInt($(this).attr('data-numero-question')) + 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_questions\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) + 1; return '[mod_questions][' + num + ']';} ) );
				});
				$(this).children('[name]').each( function() {
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) + 1);
					}
				});
				
				$(this).next().find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_questions\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) + 1; return '[mod_questions][' + num + ']';} ) );
				});
			}
		});
	}
	
	this.decrementQuestions = function() {
		var numero = this.numero;
		this.selector.siblings('[data-numero-question]').each( function() {
			if ( $(this).attr('data-numero-question') > numero ) {
			
				$(this).attr('data-numero-question', parseInt($(this).attr('data-numero-question')) - 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_questions\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) - 1; return '[mod_questions][' + num + ']';} ) );
				});
				$(this).children('[name]').each( function() {
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) - 1);
					}
				});
				
				$(this).next().find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_questions\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) - 1; return '[mod_questions][' + num + ']';} ) );
				});
			}
		});
	}
}

function NewQuestion(numero) {			
	this.numero = numero,
	this.loop = parseInt(this.numero) - 1;
	
	var removeQuestionButton = new RemoveQuestionButton();
	var addQuestionButton = new AddQuestionButton();
	var newMacroNormal = new NewMacro(this, 'normal', 1, 0, 0, 0, 0);
	var newMacroIndice = new NewMacro(this, 'normal', 2, 0, 0, 0, 4);
	var newMacroCorrection = new NewMacro(this, 'normal', 3, 0, 0, 0, 5);
	this.content = '<div class="question row" data-numero-question="' + this.numero + '"> <br> <input type="hidden" value="' + this.numero + '" name="exerciceformulairetype[mod_questions][' + this.loop + '][numero]"> <div class="col-lg-1">' + removeQuestionButton.content + addQuestionButton.content + '<br><i class="icon-remove icon-large text-light-grey" data-title="Question non commençée" rel="tooltip"></i> <span class="badge" data-title="Nombre d\'essai(s)" rel="tooltip">0</span> </div> <div class="col-lg-6">' + newMacroNormal.content + '</div> <div class="col-lg-5">' + newMacroIndice.content + newMacroCorrection.content + '</div> </div> <div class="reponse row"> <br> <div class="col-md-offset-1 col-lg-10 well"> <table class="table"> <thead> <tr> <th>#</th> <th>Réponse</th> <th>Type de réponse</th> <th>Dépendances</th> </tr> </thead> <tbody><tr> <td> </td>  <td> </td>  <td> <strong>Type de dépendance :</strong><br><br> Association <br>Association par groupe<br> Fraction<br> Permutation circulaire<br> Permutation circulaire par couple<br> Permutation totale<br> Permutation totale par couple </td>  <td class="dependance_td"> <div class="pull-left"> <br><br> - <br> - <br> - <br> - <br> - <br> - </div> </td> </tr></tbody> </table> </div> </div>';
}

function RemoveQuestionButton() {
	this.content = '<button type="button" rel="tooltip" data-title="Supprimer cette question" class="btn btn-danger btn-sm question-interaction-btn remove-question invisible"><i class="icon-remove"></i></button>';
}

function AddQuestionButton() {
	this.content = '<div class="btn-group"> <button rel="tooltip" data-title="Insérer une nouvelle question" type="button" class="btn btn-default btn-sm question-interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li class="dropdown-header">Structure exercice</li> <li><a tabindex="-1" class="add-question cursor"><i class="icon-plus"></i> Ajouter une question</a></li> </ul> </div>';
}

function Macro(question, selector) {
	this.question = question,
	this.selector = selector,
	this.type = this.selector.attr('data-type-macro'),
	this.numero = parseInt(selector.attr('data-numero-macro')),
	this.loop = parseInt(this.numero) - 1
	
	this.incrementMacros = function() {
		var numero = this.numero;
		this.question.selector.find('div.macro[data-numero-macro]').each( function() {
			if ( $(this).attr('data-numero-macro') > numero )
			{
				$(this).attr('data-numero-macro', parseInt($(this).attr('data-numero-macro')) + 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_macros\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) + 1; return '[mod_macros][' + num + ']';} ) );
				});
				$(this).children('[name]').each( function() {
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) + 1);
					}
				});
			}
		});
	}
	
	this.decrementMacros = function() {
		var numero = this.numero;
		this.question.selector.find('div.macro[data-numero-macro]').each( function() {
			if ( $(this).attr('data-numero-macro') > numero )
			{
				$(this).attr('data-numero-macro', parseInt($(this).attr('data-numero-macro')) - 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_macros\]\[(\d{1,2})\]/g, function(match, number) { var num = parseInt(number) - 1; return '[mod_macros][' + num + ']';} ) );
				});
				$(this).children('[name]').each( function() {
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) - 1);
					}
				});
			}
		});
	}
	
	this.getPrecedingInputsNumber = function() {
		var precInputs = 0;
		this.selector.prevAll().each( function() {
			if ($(this).find('.radiotick').length != 0) {
				precInputs++;
			}
			else {
				precInputs = precInputs + $(this).children('.input-eleve').length;
			}
		});
		return precInputs;
	}
	
	this.getInputsNumber = function() {
		if (this.selector.find('.radiotick').length != 0) {
			return 1;
		}
		else {
			return this.selector.children('.input-eleve').length;
		}
	}
}

function RemoveMacroButton() {
	this.content = '<button type="button" rel="tooltip" data-title="Supprimer ce Macro-élément" class="btn btn-danger btn-sm macro-interaction-btn remove-macro invisible"><i class="icon-remove"></i></button>';
}

function AddMacroButton() {
	this.content = '<div class="btn-group"> <button rel="tooltip" data-title="Insérer un nouveau Macro-élément" type="button" class="btn btn-primary btn-sm macro-interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li class="dropdown-header">Nouveau Macro-élément</li> <li><a tabindex="-1" class="add-macro-normal cursor"><i class="icon-th-large"></i> Créer un Macro-élément de base</a></li> <li> <a href="#add-radio" tabindex="-1" data-toggle="modal" class="cursor add-radio"><i class="icon-circle-blank"></i> Créer un Macro-élément Radio</a> </li> <li> <a href="#add-checkbox" tabindex="-1" data-toggle="modal" class="cursor add-checkbox"><i class="icon-check"></i> Créer un Macro-élément Checkbox</a> </li> <li> <a href="#add-vignettes" tabindex="-1" data-toggle="modal" class="cursor add-vignettes"><i class="icon-reorder"></i> Créer un Macro-élément Vignettes</a> </li> <li><a tabindex="-1" class="add-macro-fraction cursor"><i class="icon-check-empty"></i>/<i class="icon-check-empty"></i> Créer une fraction</a></li> <li><a tabindex="-1" class="add-macro-integrale cursor">∫ Créer une intégrale</a></li> <li> <a href="#add-tableau" tabindex="-1" data-toggle="modal" class="cursor add-tableau"><i class="icon-table"></i> Créer un tableau</a> </li> <li> <a href="#add-tableau-analyse" tabindex="-1" data-toggle="modal" class="cursor add-tableau-analyse"><i class="icon-table"></i>↗± Créer un tableau d\'analyse</a> </li> </div>';
}

function AddPeriphericMacroButton() {
	this.content = '<div class="btn-group"> <button rel="tooltip" data-title="Insérer un nouveau Macro-élément" type="button" class="btn btn-primary btn-sm macro-interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li class="dropdown-header">Nouveau Macro-élément</li> <li><a tabindex="-1" class="add-macro-normal cursor"><i class="icon-th-large"></i> Créer un Macro-élément de base</a></li> <li> <a href="#add-tableau" tabindex="-1" data-toggle="modal" class="cursor add-tableau"><i class="icon-table"></i> Créer un tableau</a> </li> <li> <a href="#add-tableau-analyse" tabindex="-1" data-toggle="modal" class="cursor add-tableau-analyse"><i class="icon-table"></i>↗± Créer un tableau d\'analyse</a> </li> </div>';
}

function NewMacro(question, type, numero, input_number, nombre_lignes, nombre_colonnes, fond) {
	this.question = question,
	this.type = type,
	this.numero = numero,
	this.loop = parseInt(this.numero) - 1;
	var removeMacroButton = new RemoveMacroButton();
	
	if (fond == 0) {
		var addMacroButton = new AddMacroButton();
		this.header = '<div class="macro" data-type-macro="' + this.type + '" data-numero-macro="' + this.numero + '" data-fond="' + fond + '">' + removeMacroButton.content + ' ' + addMacroButton.content + '<div class="col-lg-2 pull-right"><input type="number" rel="tooltip" data-title="0 : consigne de la question (tjs affichée); 1,2,3.. : numéro des sous-questions" class="form-control invisible macro-interaction-btn" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][couche]" value="1" /></div> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][numero]" value="' + this.numero + '" /> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][fond]" value="' + fond + '" /> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][type]" value="' + this.type + '" />';
	}
	else {
		var addPeriphericMacroButton = new AddPeriphericMacroButton();
		if (this.question.numero == 0) {
			this.header = '<div class="macro" data-type-macro="' + this.type + '" data-numero-macro="' + this.numero + '" data-fond="' + fond + '">' + removeMacroButton.content + ' ' + addPeriphericMacroButton.content + '<input type="hidden" name="exerciceformulairetype[mod_exercice][mod_macros][' + this.loop + '][couche]" value="0" /> <input type="hidden" name="exerciceformulairetype[mod_exercice][mod_macros][' + this.loop + '][numero]" value="' + this.numero + '" /> <input type="hidden" name="exerciceformulairetype[mod_exercice][mod_macros][' + this.loop + '][fond]" value="' + fond + '" /> <input type="hidden" name="exerciceformulairetype[mod_exercice][mod_macros][' + this.loop + '][type]" value="' + this.type + '" />';
		}
		else {
			this.header = '<div class="macro" data-type-macro="' + this.type + '" data-numero-macro="' + this.numero + '" data-fond="' + fond + '">' + removeMacroButton.content + ' ' + addPeriphericMacroButton.content + '<div class="col-lg-2 pull-right"><input type="number" rel="tooltip" data-title="0 : consigne de la question (tjs affichée); 1,2,3.. : numéro des sous-questions" class="form-control invisible macro-interaction-btn" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][couche]" value="1" /></div> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][numero]" value="' + this.numero + '" /> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][fond]" value="' + fond + '" /> <input type="hidden" name="exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.loop + '][type]" value="' + this.type + '" />';
		}
	}

	if (type == 'normal') {
		var textareaElement = new NewElement(this.question, this, 'text', 1);
		this.content = this.header + textareaElement.content + '</div>';
	}
	else if (type == 'indice') {
		var textareaElement = new NewElement(this.question, this, 'text', 1);
		this.content = '<div class="alert alert-warning">' + this.header + textareaElement.content + '</div> </div>';
	}
	else if (type == 'correction') {
		var textareaElement = new NewElement(this.question, this, 'text', 1);
		this.content = '<div class="alert alert-success">' + this.header + textareaElement.content + '</div> </div>';
	}
	else if (type == 'radio') {
		this.content = this.header+'<span class="input-eleve"></span>';
		for(var i=0;i<=input_number-1;i++) {
			var indice1 = 2*i+1;
			var indice2 = 2*i+2;
			var radiotickElement = new NewElement(this.question, this, 'radiotick', indice1);
			var brElement = new NewElement(this.question, this, 'br', indice2);
			this.content = this.content + radiotickElement.content + brElement.content;
		}
		this.content = this.content + '</div>';
	}
	else if (type == 'checkbox') {
		this.content = this.header;
		for(var i=0;i<=input_number-1;i++) {
			var indice1 = 2*i+1;
			var indice2 = 2*i+2;
			var checkboxtickElement = new NewElement(this.question, this, 'checkboxtick', indice1);
			var brElement = new NewElement(this.question, this, 'br', indice2);
			this.content = this.content + checkboxtickElement.content + brElement.content;
		}
		this.content = this.content + '</div>';
	}
	else if (type == 'vignettes') {
		this.content = this.header;
		for(var i=0;i<=input_number-1;i++) {
			var indice1 = 2*i+1;
			var indice2 = 2*i+2;
			var vignetteElement = new NewElement(this.question, this, 'vignette', indice1, i+1);
			var textareaElement = new NewElement(this.question, this, 'text', indice2, i+1);
			this.content = this.content + vignetteElement.content + textareaElement.content;
		}
		this.content = this.content + '</div>';
	}
	else if (type == 'fraction') {
		var numerateurElement = new NewElement(this.question, this, 'numerateur', 1);
		var denominateurElement = new NewElement(this.question, this, 'denominateur', 2);
		this.content = this.header + numerateurElement.content + denominateurElement.content + '</div>';
	}
	else if (type == 'integrale') {
		var mathsElement = new NewElement(this.question, this, 'maths', 1);
		this.content = this.header + '<br>∫ <em>Intégrale entre [a] de [expression] et [b], (variable: [x])</em>' + mathsElement.content + '</div>';
	}
	else if (type == 'tableau') {
		this.content = this.header;
		for(var i=0;i<=nombre_lignes-1;i++) {
			var numero_ligne1 = i+i*2*nombre_colonnes+1;
			var trElement = new NewElement(this.question, this, 'tr', numero_ligne1);
			this.content = this.content + trElement.content;
			for(var j=0;j<=nombre_colonnes-1;j++) {
				var numero_colonne1 = 2*j+1+numero_ligne1;
				var numero_colonne2 = 2*j+2+numero_ligne1;
				var tdElement = new NewElement(this.question, this, 'td', numero_colonne1);
				var mathsareaElement = new NewElement(this.question, this, 'maths', numero_colonne2);
				this.content = this.content + tdElement.content + mathsareaElement.content;
			}
		}
		this.content = this.content + '</div>';
	}
	else if (type == 'tableau analyse') {
		console.log('wtf???');
		this.content = this.header;
		for(var i=0;i<=nombre_lignes-1;i++) {
			var numero_ligne = i+i*nombre_colonnes+1;
			var trElement = new NewElement(this.question, this, 'tr', numero_ligne);
			this.content = this.content + trElement.content;
			for(var j=0;j<=nombre_colonnes-1;j++) {
				var numero_colonne = j+1+numero_ligne;
				var tdElement = new NewElement(this.question, this, 'td', numero_colonne);
				this.content = this.content + tdElement.content;
			}
		}
		this.content = this.content + '</div>';
	}
}

function Mapping(question, loop) {	
	this.question = question,
	this.loop = loop
}

function Element(question, macro, selector) {	
	this.question = question,
	this.macro = macro,
	this.selector = selector,
	this.numero = parseInt(selector.attr('data-numero-element'))
	
	this.incrementSiblings = function() {
		var numero = this.numero;
		this.selector.siblings('div.element[data-numero-element]').each( function() {
			if ( $(this).attr('data-numero-element') > numero )
			{
				$(this).attr('data-numero-element', parseInt($(this).attr('data-numero-element')) + 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_elements\]\[(\d{1,3})\]/g, function(match, number) { var num = parseInt(number) + 1; return '[mod_elements][' + num + ']';} ) );
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) + 1);
					}
				});
			}
		});
	}
	
	this.decrementSiblings = function() {
		var numero = this.numero;
		this.selector.siblings('div.element[data-numero-element]').each( function() {
			if ( $(this).attr('data-numero-element') > numero )
			{
				$(this).attr('data-numero-element', parseInt($(this).attr('data-numero-element')) - 1);
				$(this).find('[name]').each( function() {
					$(this).attr('name', $(this).attr('name').replace(/\[mod_elements\]\[(\d{1,3})\]/g, function(match, number) { var num = parseInt(number) - 1; return '[mod_elements][' + num + ']';} ) );
					if ( $(this).attr('name').slice(-7, -1) == 'numero' ) {
						$(this).val( parseInt($(this).val()) - 1);
					}
				});
			}
		});
	}
	
	this.getPrecedingInputsNumber = function() {
		if (this.selector.hasClass('input-eleve'))
		{
			var isInput = 1;
		}
		else
		{
			var isInput = 0;
		}
		//console.log('isInput : '+isInput+' '+this.selector.prevAll('.input-eleve').length+' '+this.macro.selector.prevAll().children('.input-eleve').length);
		return isInput + this.selector.prevAll('.input-eleve').length + this.macro.selector.prevAll().children('.input-eleve').length;
	}
}

function RemoveElementButton() {
	this.content = '<button type="button" rel="tooltip" data-title="Supprimer cet élément" class="btn btn-danger btn-sm interaction-btn remove-element invisible"><i class="icon-remove"></i></button> ';
}

function RemoveElementAndInputButton() {
	this.content = '<button type="button" rel="tooltip" data-title="Supprimer cet élément-input" class="btn btn-danger btn-sm interaction-btn remove-element-and-reponse invisible"><i class="icon-remove"></i></button> ';
}

function AddElementButton(question, macro) {
	if (question.numero == 0 || macro.type == 'indice' || macro.type == 'correction') {
		this.content = '<div class="btn-group"> <button rel="tooltip" data-title="Insérer un nouvel élément" type="button" class="btn btn-info btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li class="dropdown-header">Construction énoncé</li> <li><a tabindex="-1" class="add-text"><i class="icon-font"></i> Elément de texte</a></li> <li><a tabindex="-1" class="add-maths"><i class="icon-magic"></i> Formule de maths</a></li> <li><a tabindex="-1" class="add-br"><i class="icon-level-down icon-rotate-90"></i> Retour à la ligne</a></li> <li> <a href="#add-figure-graph" tabindex="-1" data-toggle="modal" class="add-figure-graphe"><i class="icon-bar-chart"></i> Figure / Graphe</a> </li> <li><a tabindex="-1"><i class="icon-link"></i> Lien</a></li> <li><a tabindex="-1"><i class="icon-picture"></i> Image</a></li> </ul> </div>';
	}
	else {
		this.content = '<div class="btn-group"> <button rel="tooltip" data-title="Insérer un nouvel élément" type="button" class="btn btn-info btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li class="dropdown-header">Construction énoncé</li> <li><a tabindex="-1" class="add-text"><i class="icon-text-width"></i> Elément de texte</a></li> <li><a tabindex="-1" class="add-maths"><i class="icon-magic"></i> Formule de maths</a></li> <li><a tabindex="-1" class="add-br"><i class="icon-level-down icon-rotate-90"></i> Retour à la ligne</a></li> <li> <a href="#add-figure-graph" tabindex="-1" data-toggle="modal" class="add-figure-graphe"><i class="icon-bar-chart"></i> Figure / Graphe</a> </li> <li><a tabindex="-1"><i class="icon-link"></i> Lien</a></li> <li><a tabindex="-1"><i class="icon-picture"></i> Image</a></li> <li class="divider"></li> <li class="dropdown-header">Réponse élève</li> <li> <a href="#add-case" tabindex="-1" data-toggle="modal" class="add-case"><i class="icon-pencil"></i> Case</a> </li> <li> <a href="#add-case-puissance" tabindex="-1" data-toggle="modal" class="add-case-puissance"><i class="icon-pencil"></i> Case puissance</a> </li> <li> <a href="#add-case-indice" tabindex="-1" data-toggle="modal" class="add-case-indice"><i class="icon-pencil"></i> Case indice</a> </li> <li> <a href="#add-liste-deroulante" tabindex="-1" data-toggle="modal" class="add-liste-deroulante"><i class="icon-list-ul"></i> Liste déroulante</a> </li> </ul> </div>';
	}
}

function NewElement(question, macro, type, numero, numero_vignette, clavier, liste_der_content) {	
	this.question = question,
	this.macro = macro,
	this.type = type,
	this.numero = numero,
	this.loop = parseInt(this.numero) - 1;
	
	if (this.question.numero == 0)
	{
		this.form_name = 'exerciceformulairetype[mod_exercice][mod_macros][' + this.macro.loop + '][mod_elements][' + this.loop + ']';
	}
	else {
		this.form_name = 'exerciceformulairetype[mod_questions][' + this.question.loop + '][mod_macros][' + this.macro.loop + '][mod_elements][' + this.loop + ']';
	}
	
	var removeElementButton = new RemoveElementButton();
	var removeElementAndInputButton = new RemoveElementAndInputButton();
	var addElementButton = new AddElementButton(question, macro);

	if (this.type == 'text') {
		this.content = '<div class="element text cursor" data-numero-element="' + this.numero + '" ';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + 'data-numero-vignette="' + numero_vignette + '" ';
		}
		this.content = this.content + '> ' + removeElementButton.content + ' ' + addElementButton.content + '<br> <textarea name="' + this.form_name + '[contenu]" class="form-control" rows="1" placeholder="Texte"></textarea> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /> <input type="hidden" name="' + this.form_name + '[type]" value="text" />';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + '<input type="hidden" name="' + this.form_name + '[clavier]" value="vignette-' + numero_vignette + '" />';
		}
		this.content = this.content + '</div>';
	}	
	else if (this.type == 'maths') {
		this.content = '<div class="element maths cursor" data-numero-element="' + this.numero + '" ';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + 'data-numero-vignette="' + numero_vignette + '" ';
		}
		this.content = this.content + '> ' + removeElementButton.content + ' ' + addElementButton.content + '<br> <div class="mathscase case cursor" data-clavier="general" data-layer="0"><math></math></div> <input type="hidden" name="' + this.form_name + '[contenu]" value="" /> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="' + this.type + '" />';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + '<input type="hidden" name="' + this.form_name + '[clavier]" value="vignette-' + numero_vignette + '" />';
		}
		this.content = this.content + '</div>';
	}
	else if (this.type == 'br')  {
		this.content = '<div class="element maths cursor" data-numero-element="' + this.numero + '" ';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + 'data-numero-vignette="' + numero_vignette + '" ';
		}
		this.content = this.content + '> ' + removeElementButton.content + ' ' + addElementButton.content + '<br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="' + this.type + '" />';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + '<input type="hidden" name="' + this.form_name + '[clavier]" value="vignette-' + numero_vignette + '" />';
		}
		this.content = this.content + '<i class="icon-level-down icon-rotate-90"></i></div>';
	}
	else if (this.type == 'liste_der')  {
		this.content = '<div class="element input-eleve cursor" data-numero-element="' + this.numero + '"> ' + removeElementAndInputButton.content + ' ' + addElementButton.content + '<br> <textarea name="' + this.form_name + '[contenu]" class="form-control" rows="1" placeholder="Liste déroulante : (séparer les réponses possibles par des ##)">'+liste_der_content+'</textarea> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" />  <div class="col-lg-2">Taille : </div><div class="col-lg-2"><select name="' + this.form_name + '[clavier]" class="form-control"><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></div><br><br> <input type="hidden" name="' + this.form_name + '[type]" value="liste_der" /><br><br></div>';
	}
	else if (this.type == 'case')  {
		this.content = '<div class="element input-eleve cursor" data-numero-element="' + this.numero + '" >' + removeElementAndInputButton.content + ' ' + addElementButton.content + '<br> <div class="case cursor"></div><input type="hidden" name="' + this.form_name + '[clavier]" value="' + clavier + '" /><input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="' + this.type + '" /></div>';
	}
	else if (this.type == 'case puissance')  {
		this.content = '<div class="element input-eleve cursor" data-numero-element="' + this.numero + '" >' + removeElementAndInputButton.content + ' ' + addElementButton.content + '<br> <div class="case-puissance cursor"></div><input type="hidden" name="' + this.form_name + '[clavier]" value="' + clavier + '" /><input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="' + this.type + '" /></div>';
	}
	else if (this.type == 'case indice')  {
		this.content = '<div class="element input-eleve cursor" data-numero-element="' + this.numero + '" >' + removeElementAndInputButton.content + ' ' + addElementButton.content + '<br> <div class="case-indice cursor"></div><input type="hidden" name="' + this.form_name + '[clavier]" value="' + clavier + '" /><input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="' + this.type + '" /></div>';
	}
	else if (this.type == 'vignette')  {
		this.content = '<div class="element vignette input-eleve cursor" data-numero-element="' + this.numero + '" ';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + 'data-numero-vignette="' + numero_vignette + '" ';
		}
		this.content = this.content + '> ' + addElementButton.content + '<br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="vignette" />';
		if ( numero_vignette != 'undefined' && numero_vignette > 0 ) {
			this.content = this.content + '<input type="hidden" name="' + this.form_name + '[clavier]" value="vignette-' + numero_vignette + '" />';
		}
		this.content = this.content + '<i class="icon-reorder"></i> <i>Nouvelle vignette</i> </div>';
	}
	else if (this.type == 'radiotick')  {
		this.content = '<div class="element radiotick cursor" data-numero-element="' + this.numero + '" > ' + addElementButton.content + ' <br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="radiotick" /><input type="radio" /></i></div>';
	}
	else if (this.type == 'checkboxtick')  {
		this.content = '<div class="element checkboxtick input-eleve cursor" data-numero-element="' + this.numero + '" > ' + addElementButton.content + ' <br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="checkboxtick" /><input type="checkbox" /></i></div>';
	}
	else if (this.type == 'numerateur')  {
		this.content = '<div class="element numerateur cursor" data-numero-element="' + this.numero + '" > ' + addElementButton.content + ' <br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="numerateur" /> <i class="icon-check-empty"></i>/ <i>Numérateur</i> </div>';
	}
	else if (this.type == 'denominateur')  {
		this.content = '<div class="element denominateur cursor" data-numero-element="' + this.numero + '" > ' + addElementButton.content + ' <br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="denominateur" /> /<i class="icon-check-empty"></i> <i>Dénominateur</i> </div>';
	}
	else if (this.type == 'tr')  {
		this.content = '<div class="element tr cursor" data-numero-element="' + this.numero + '" > <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="tr" /> <i class="icon-ellipsis-horizontal"></i> <i>Ligne</i> </div>';
	}
	else if (this.type == 'td')  {
		this.content = '<div class="element td cursor" data-numero-element="' + this.numero + '" > ' + addElementButton.content + ' <br> <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /><input type="hidden" name="' + this.form_name + '[type]" value="td" /> <i class="icon-check-empty"></i> <i>Case</i> </div>';
	}
	else if (this.type == 'jsgbox')  {
		this.content = '<div class="element jsgbox cursor" data-numero-element="' + this.numero + '" >' + removeElementAndInputButton.content + ' ' + addElementButton.content + ' <div class="btn-group"><button rel="tooltip" data-title="Ajouter un point" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-circle"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-point-by-click">En cliquant</a></li> <li><a tabindex="-1" class="add-jsg-point-by-coords">Par ses coordonnées</a></li> </ul></div> <div class="btn-group"><button rel="tooltip" data-title="Ajouter un segment" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">↔</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-segment-by-click">En cliquant</a></li> <li><a tabindex="-1" class="add-jsg-segment-by-points">Entre 2 points</a></li> </ul></div> <div class="btn-group"><button rel="tooltip" data-title="Ajouter une droite" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">/</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-droite-by-click">En cliquant</a></li> <li><a tabindex="-1" class="add-jsg-droite-by-points">Entre 2 points</a></li> </ul></div><div class="btn-group"><button rel="tooltip" data-title="Ajouter un cercle" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-circle-blank"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-circle-by-centre-point">par le centre et 1 point du cercle</a></li> <li><a tabindex="-1" class="add-jsg-circle-by-centre-rayon">par le centre et le rayon</a></li> <li><a tabindex="-1" class="add-jsg-circle-by-3points">par 3 points</a></li> </ul></div> <button type="button" rel="tooltip" data-title="Ajouter un angle" class="btn btn-default btn-sm interaction-btn add-jsg-angle invisible">∡</button> <button type="button" rel="tooltip" data-title="Ajouter du texte" class="btn btn-default btn-sm interaction-btn add-jsg-text invisible"><i class="icon-font"></i></button> <div class="btn-group"><button rel="tooltip" data-title="Ajouter une règle graduée" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">../..</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-grad-jsg-1">Regle 1</a></li> <li><a tabindex="-1" class="add-grad-jsg-2">Regle 2</a></li> <li><a tabindex="-1" class="add-grad-jsg-3">Regle 3</a></li> <li><a tabindex="-1" class="add-grad-jsg-4">Regle 4</a></li> </ul></div> <button type="button" rel="tooltip" data-title="Ajouter un segment ouv/fer" class="btn btn-default btn-sm interaction-btn add-segment-ouv-fer invisible">(-(</button> <button type="button" rel="tooltip" data-title="Annuler la dernière modification" class="btn btn-default btn-sm interaction-btn undo-jsg invisible"><i class="icon-undo"></i></button> <br> <div id="' + clavier + '" class="jsgcontainer border-grey-rounded row-container"></div> <input type="hidden" name="' + this.form_name + '[contenu]" > <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /> <input type="hidden" name="' + this.form_name + '[type]" value="jsgbox" /> <input type="hidden" name="' + this.form_name + '[clavier]" value="' + clavier + '" /> </div>';
	}
	else if (this.type == 'jsggraph')  {
		this.content = '<div class="element jsggraph cursor" data-numero-element="' + this.numero + '" >' + removeElementAndInputButton.content + ' ' + addElementButton.content + ' <button rel="tooltip" data-title="Ajouter un point" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle add-jsg-point-by-coords" data-toggle="dropdown"><i class="icon-circle"></i></button> <div class="btn-group"><button rel="tooltip" data-title="Ajouter un segment" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">↔</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-segment-by-coords">Par ses coordonnées</a></li> <li><a tabindex="-1" class="add-jsg-segment-by-points">Entre 2 points</a></li> </ul></div><div class="btn-group"><button rel="tooltip" data-title="Ajouter une droite" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">/</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-droite-by-coords">Par ses coordonnées</a></li> <li><a tabindex="-1" class="add-jsg-droite-by-points">Entre 2 points</a></li> </ul></div><div class="btn-group"><button rel="tooltip" data-title="Ajouter un vecteur" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown">↗</button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-vecteur-by-coords">Par ses coordonnées</a></li> <li><a tabindex="-1" class="add-jsg-vecteur-by-points">Entre 2 points</a></li> </ul></div><div class="btn-group"><button rel="tooltip" data-title="Ajouter un cercle" type="button" class="btn btn-default btn-sm interaction-btn invisible dropdown-toggle" data-toggle="dropdown"><i class="icon-circle-blank"></i></button> <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-jsg-circle-by-centre-point">par le centre et 1 point du cercle</a></li> <li><a tabindex="-1" class="add-jsg-circle-by-centre-rayon">par le centre et le rayon</a></li> <li><a tabindex="-1" class="add-jsg-circle-by-3points">par 3 points</a></li> </ul></div> <button type="button" rel="tooltip" data-title="Ajouter une fonction" class="btn btn-default btn-sm interaction-btn add-jsg-fonction invisible" style="font-size:16px;"><em>f</em></button> <button type="button" rel="tooltip" data-title="Ajouter une intégrale (aire)" class="btn btn-default btn-sm interaction-btn add-jsg-integrale invisible" style="font-size:14px;">∫</button> <button type="button" rel="tooltip" data-title="Ajouter du texte" class="btn btn-default btn-sm interaction-btn add-jsg-text-by-coords invisible"><i class="icon-font"></i></button> <button type="button" rel="tooltip" data-title="Annuler la dernière modification" class="btn btn-default btn-sm interaction-btn undo-jsg invisible"><i class="icon-undo"></i></button> <br> <div id="' + clavier + '" class="jsgcontainer border-grey-rounded row-container"></div> <input type="hidden" name="' + this.form_name + '[contenu]" > <input type="hidden" name="' + this.form_name + '[numero]" value="' + this.numero + '" /> <input type="hidden" name="' + this.form_name + '[type]" value="jsggraph" /> <input type="hidden" name="' + this.form_name + '[clavier]" value="' + clavier + '" /> </div>';
	}
}

function ExistingReponse(question, mapping, selector) {		
	this.question = question,
	this.mapping = mapping,
	this.selector = selector,
	this.clavier = selector.find('.mathscase').attr('data-clavier'),
	this.numero = parseInt(selector.attr('data-numero-reponse')),
	this.numeroMoins = this.numero - 1,
	this.loop = parseInt(selector.attr('data-loop-reponse'))
	
	this.getNumeroReponseCount = function() {
		var numeroReponseCount = 0;
		var thisNumero = this.numero
		this.selector.closest('tbody').children().each( function() {
			if ( $(this).attr('data-numero-reponse') == thisNumero ) {
				numeroReponseCount++;
			}
		});
		return numeroReponseCount;
	}
	
	this.getHighestLoopMapping = function() {
		var highestLoopMapping = 0;
		var thisNumero = this.numero;
		this.selector.closest('tbody').children().each( function() {
			if ( $(this).attr('data-numero-reponse') == thisNumero ) {
				highestLoopMapping = Math.max(highestLoopMapping, $(this).attr('data-loop-reponse'));
			}
		});
		return highestLoopMapping;
	}
	
	this.reorganizeCheckReponseBeforeDeletion = function() {
		if ( this.selector.find('input[type="checkbox"]:checked').length > 0 )
		{				
			var checkedNumero = this.selector.find('input[type="checkbox"]:checked').attr('data-check-numero');
			if ( this.selector.closest('tbody').find('input[data-check-numero="' + checkedNumero + '"]:checked').length == 2 )
			{			
				this.selector.siblings('tr[data-numero-reponse="' + this.numero + '"]').each( function() {
					if ( $(this).find('td:last-child').children(':checked').length == 0 )
					{
						$(this).find('input[data-check-numero="' + checkedNumero + '"]').each( function() {
							$(this).removeAttr('disabled');
						});
					}
				});
				this.selector.closest('tbody').find('input[data-radio-numero = "' + checkedNumero + '"]').parent().remove(); 
				 
				this.selector.closest('tbody').find('input[data-check-numero="' + checkedNumero + '"]').each( function() {
					if ( $(this).is(':checked') )
					{
						$(this).siblings().removeAttr('disabled');
					}
					$(this).remove();
				});
			}
		}
	}
}

function NewReponse(question, mapping, numero, loop, dependances, clavier, choices_number) {
	this.question = question,
	this.mapping = mapping,
	this.numero = numero,
	this.loop = loop,
	this.dependances = dependances,
	this.clavier = clavier;
	
	if (clavier.substring(0, 5) == 'radio') {
		this.content = '<tr data-loop-mapping="'+this.mapping.loop+'" data-loop-reponse="'+this.loop+'" data-numero-reponse="'+this.numero+'"><td><strong>'+this.numero+'</strong><input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][numero]" value="'+this.numero+'" /></td><td><select name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][contenu]" class="form-control">';
		for(var i = 1; i <= choices_number; i++) {
			this.content = this.content +'<option value="'+i+'">'+i+'</option>'
		}
		this.content = this.content +'</select> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][clavier]" value="'+this.clavier+'" /> </td><td><select class="form-control" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][type]"><option value="expression exacte">Expression exacte</option><option value="expression">Expression</option><option value="triangle">Triangle</option><option value="angle">Angle</option><option value="mot">Mot</option><option value="booleen">Booléen</option><option value="radio" selected="selected">Radio</option><option value="checkbox">Checkbox</option><option value="liste">Liste déroulante</option><option value="vignette">Vignette</option></select></td><td>'+this.dependances+'</td></tr>';
	}
	else if (clavier == 'liste_der') {
		this.content = '<tr data-loop-mapping="'+this.mapping.loop+'" data-loop-reponse="'+this.loop+'" data-numero-reponse="'+this.numero+'"><td><strong>'+this.numero+'</strong><input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][numero]" value="'+this.numero+'" /></td><td><select name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][contenu]" class="form-control">';
		for(var i = 1; i <= 10; i++) {
			this.content = this.content +'<option value="'+i+'">'+i+'</option>'
		}
		this.content = this.content +'</select> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][clavier]" value="'+this.clavier+'" /> </td><td><select class="form-control" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][type]"><option value="expression exacte">Expression exacte</option><option value="expression">Expression</option><option value="triangle">Triangle</option><option value="angle">Angle</option><option value="mot">Mot</option><option value="booleen">Booléen</option><option value="radio">Radio</option><option value="checkbox">Checkbox</option><option value="liste" selected="selected">Liste déroulante</option><option value="vignette">Vignette</option></select></td><td>'+this.dependances+'</td></tr>';
	}
	else if (clavier.substring(0, 9) == 'vignettes') {
		this.content = '<tr data-loop-mapping="'+this.mapping.loop+'" data-loop-reponse="'+this.loop+'" data-numero-reponse="'+this.numero+'"><td><strong>'+this.numero+'</strong><input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][numero]" value="'+this.numero+'" /></td><td><select name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][contenu]" class="form-control"><option value="0">Non classée</option>';
		for(var i = 1; i <= choices_number; i++) {
			this.content = this.content +'<option value="'+i+'">Vignette n°'+i+'</option>'
		}
		this.content = this.content +'</select> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][clavier]" value="'+this.clavier+'" /> </td><td><select class="form-control" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][type]"><option value="expression exacte">Expression exacte</option><option value="expression">Expression</option><option value="triangle">Triangle</option><option value="angle">Angle</option><option value="mot">Mot</option><option value="booleen">Booléen</option><option value="radio">Radio</option><option value="checkbox">Checkbox</option><option value="liste">Liste déroulante</option><option value="vignette" selected="selected">Vignette</option></select></td><td>'+this.dependances+'</td></tr>';
	}
	else if (clavier.substring(0, 8) == 'checkbox') {		
		this.content = '<tr data-loop-mapping="'+this.mapping.loop+'" data-loop-reponse="'+this.loop+'" data-numero-reponse="'+this.numero+'"><td><strong>'+this.numero+'</strong><input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][numero]" value="'+this.numero+'" /></td><td><select name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][contenu]" class="form-control"><option value="1">Cochée</option><option value="0" selected="selected">Non cochée</option></select> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][clavier]" value="'+this.clavier+'" /> </td><td><select class="form-control" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][type]" readonly><option value="expression exacte">Expression exacte</option><option value="expression">Expression</option><option value="triangle">Triangle</option><option value="angle">Angle</option><option value="mot">Mot</option><option value="booleen">Booléen</option><option value="radio">Radio</option><option value="checkbox" selected="selected">Checkbox</option><option value="liste">Liste déroulante</option><option value="vignette">Vignette</option></select></td><td>'+this.dependances+'</td></tr>';
	}
	else {
		this.content = '<tr data-loop-mapping="'+this.mapping.loop+'" data-loop-reponse="'+this.loop+'" data-numero-reponse="'+this.numero+'"><td><strong>'+this.numero+'</strong><input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][numero]" value="'+this.numero+'" /></td><td> <div class="mathscase case cursor" data-clavier="'+this.clavier+'" data-layer="0"><math></math></div> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][contenu]" /> <input type="hidden" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][clavier]" value="'+this.clavier+'" /> <button rel="tooltip" data-title="Ajouter une réponse alternative" type="button" class="btn btn-info add-reponse"><i class="icon-plus"></i></button> <button rel="tooltip" data-title="Supprimer cette réponse" type="button" class="btn btn-danger remove-reponse"><i class="icon-remove"></i></button> </td><td><select class="form-control" name="exerciceformulairetype[mod_questions]['+this.question.loop+'][mod_mappings]['+this.mapping.loop+'][mod_reponses]['+this.loop+'][type]" readonly><option value="expression exacte" selected="selected">Expression exacte</option><option value="expression">Expression</option><option value="triangle">Triangle</option><option value="angle">Angle</option><option value="mot">Mot</option><option value="booleen">Booléen</option><option value="radio">Radio</option><option value="checkbox">Checkbox</option><option value="liste">Liste déroulante</option><option value="vignette">Vignette</option></select></td><td>'+this.dependances+'</td></tr>';
	}
}

function CheckReponse(reponse, selector) {
	this.reponse = reponse,
	this.selector = selector,
	this.numero = parseInt(this.selector.attr('data-check-numero')),
	this.numeroPlus = this.numero + 1,
	
	this.setSelector = function(newSelector) {
		this.selector = newSelector;
	}
	
	this.getCheckedInColumn = function() {
		return this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]:checked').length;
	}
	
	this.getIncrementedLoopMapping = function() {
		var newLoopMapping = 0;
		this.selector.closest('tbody').children('tr').each( function() {
			if ( $(this).attr('data-loop-mapping') !== undefined )
			{
				newLoopMapping = Math.max(newLoopMapping, $(this).attr('data-loop-mapping'));
			}
		});
		return parseInt(newLoopMapping) + 1;
	}
	
	this.getAssociatedLoopMapping = function() {
		var newLoopMapping = 0;
		this.selector.closest('tbody').children('tr').not('tr[data-loop-mapping="' + this.reponse.mapping.loop + '"]').find('input[data-check-numero="' + this.numero + '"]:checked').each( function() {
			newLoopMapping = $(this).closest('tr').attr('data-loop-mapping');
		});
		return newLoopMapping;
	}
	
	this.getIncrementedLoopReponse = function() {
		var newLoopReponse = 0;
		this.selector.closest('tbody').children('tr').not('tr[data-loop-mapping="' + this.reponse.mapping.loop + '"]').find('input[data-check-numero="' + this.numero + '"]:checked').each( function() {
			newLoopReponse = Math.max(newLoopReponse, $(this).closest('tr').attr('data-loop-reponse'));
		});
		return parseInt(newLoopReponse) + 1;
	}
	
	this.replaceFormInputNamesOnCheck = function() {
		var newLoopReponse = 0;
		var newNumero = this.numero;
		var newLoopMapping = this.getIncrementedLoopMapping();
		this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]:checked').each( function() {		
			var thisValue = $(this);;
			thisValue.closest('tr').attr('data-loop-mapping', newLoopMapping).attr('data-loop-reponse', newLoopReponse);
			thisValue.closest('tr').find('input[type!="checkbox"]').each(function() {    // ATTENTION AU JOUR OU ON METTRA DES TAGS
				$(this).attr('name', $(this).attr('name')
				.replace(/mod_mappings\]\[(.){1,3}\]/g, 'mod_mappings][' + newLoopMapping + ']')
				.replace(/mod_reponses\]\[(.){1,3}\]/g, 'mod_reponses][' + newLoopReponse + ']'));
			});
			thisValue.closest('tr').find('select').each(function() { // ATTENTION AU JOUR OU ON METTRA DES TAGS
				$(this).attr('name', $(this).attr('name')
				.replace(/mod_mappings\]\[(.){1,3}\]/g, 'mod_mappings][' + newLoopMapping + ']')
				.replace(/mod_reponses\]\[(.){1,3}\]/g, 'mod_reponses][' + newLoopReponse + ']'));
			});
			console.log('this numero vaut: '+newNumero);
			thisValue.closest('tr').nextAll().find('input[data-radio-numero="' + newNumero + '"]').each(function() { // ATTENTION AU JOUR OU ON METTRA DES TAGS
				console.log('devrait fonctionner');
				$(this).attr('name', $(this).attr('name')
				.replace(/mod_mappings\]\[(.){1,3}\]/g, 'mod_mappings][' + newLoopMapping + ']'));
			});
			newLoopReponse++;
		});
	}
	
	this.replaceFormInputNamesOnUncheck = function() {
		this.selector.closest('tr').attr('data-loop-mapping', this.getIncrementedLoopMapping()).attr('data-loop-reponse', '0');				
		this.selector.closest('tr').html(this.selector.closest('tr').addClass('replacing-datas').html()
		.replace(/mod_mappings\]\[(.){1,3}\]/g, 'mod_mappings][' + this.getIncrementedLoopMapping() + ']')
		.replace(/mod_reponses\]\[(.){1,3}\]/g, 'mod_reponses][0]'));
		this.setSelector($('.replacing-datas').find('input[data-check-numero="' + this.numero + '"]'));
		$('.replacing-datas').removeClass('replacing-datas');
		this.selector.removeAttr('checked');
	}
	
	this.addColumn = function() {
		thisNumeroPlus = this.numeroPlus;
		this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]').each( function() {
			var prevChecked = false;
			$(this).prevAll('input').each( function() {
				if ( $(this).is(':checked') ) { prevChecked = true; }
			});
			
			if ( $(this).is(':checked') )
			{
				$(this).after(' <input class="dependance-checkbox" data-check-numero="' + thisNumeroPlus + '" type="checkbox" disabled="disabled" > ');
			}
			else if ( $(this).attr('disabled') )
			{
				if (prevChecked)
				{
					$(this).after(' <input class="dependance-checkbox" data-check-numero="' + thisNumeroPlus + '" type="checkbox" disabled="disabled"> ');
				}
				else
				{
					$(this).after(' <input class="dependance-checkbox" data-check-numero="' + thisNumeroPlus + '" type="checkbox" > ');
				}
			}
			else
			{
				$(this).after(' <input class="dependance-checkbox" data-check-numero="' + thisNumeroPlus + '" type="checkbox"> ');
			}
		});
		var mapping_types = new Array();
			mapping_types[0] = 'association';
			mapping_types[1] = 'association groupe';
			mapping_types[2] = 'fraction';
			mapping_types[3] = 'permut circ';
			mapping_types[4] = 'permut circ couple';
			mapping_types[5] = 'permut tot';
			mapping_types[6] = 'permut tot couple';
		var dependance_radio = '<div class="pull-left mapping-type-adjuster"><br><br>';
			for(var i=0; i<=mapping_types.length-1; i++) {
				// if (i == 1) {
					// dependance_radio = dependance_radio +'<input data-radio-numero="' + this.numero + '" type="radio" name="exerciceformulairetype[mod_questions][' + this.reponse.question.loop + '][mod_mappings][' + this.getAssociatedLoopMapping() + '][type]" value="'+mapping_types[i]+'" checked="checked" /><br>'
				// }
				// else {
					dependance_radio = dependance_radio +'<input data-radio-numero="' + this.numero + '" type="radio" name="exerciceformulairetype[mod_questions][' + this.reponse.question.loop + '][mod_mappings][' + this.getAssociatedLoopMapping() + '][type]" value="'+mapping_types[i]+'" /><br>'
				// }
			}
			dependance_radio = dependance_radio +'</div>';
		
		this.selector.closest('tbody').find('td.dependance_td div:last-child').before(dependance_radio);
	}
	
	this.removeColumn = function() {		
		this.selector.closest('tbody').find('input[data-radio-numero = "' + this.numero + '"]').parent().remove(); 
		 
		this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]').each( function() {
			$(this).remove();
		});
	}
	
	this.disableAltReponsesInColumn = function() {
		thisNumero = this.numero;
		this.selector.closest('tr').siblings('tr[data-numero-reponse="' + this.reponse.numero + '"]').each( function() {
			$(this).find('input[data-check-numero="' + thisNumero + '"]').each( function() {
				$(this).attr('disabled', 'disabled');
			});
		});
	}
	
	this.disableOtherChecksInLine = function() {
		this.selector.siblings('input').each( function() {
			$(this).attr('disabled', 'disabled');
		});
	}
	
	this.enableAltReponsesInColumn = function() {
		this.selector.closest('tr').siblings('tr[data-numero-reponse="' + this.reponse.numero + '"]').each( function() {
			if ( $(this).find('td:last-child').children(':checked').length == 0 )
			{
				$(this).find('input[data-check-numero="' + this.numero + '"]').each( function() {
					$(this).removeAttr('disabled');
				});
			}
		});
	}
	
	this.enableOtherChecksInLine = function() {
		this.selector.siblings('input').each( function() {
			$(this).removeAttr('disabled');
		});
	}
}