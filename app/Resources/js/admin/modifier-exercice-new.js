/* CHAPITRES
 * ========= */

$('#modexercicetype_programme').change( function() {
	var id_matiere = $('#modexercicetype_matiere').val();
	var id_programme = $('#modexercicetype_programme').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
		success: function(data){
			$('#modexercicetype_chapitre').html(data.html);
			$('#modexercicetype_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
		},
		error: function() {
			alert('Sélectionner une matière');
		}
	});
});

$('#modexercicetype_matiere').change( function() {
	var id_matiere = $('#modexercicetype_matiere').val();
	var id_programme = $('#modexercicetype_programme').val();
	if (id_programme !== null) {
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_populate_chapitres", {'id_matiere' : id_matiere, 'id_programme' : id_programme}),
			success: function(data){
				$('#modexercicetype_chapitre').html(data.html);
				$('#modexercicetype_partie').html('<option  disabled="disabled" selected="selected">Choisir une partie</option>');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	}
});

/* PARTIES
 * ======= */

$('#modexercicetype_chapitre').change( function() {
	var id_chapitre = $('#modexercicetype_chapitre').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_parties", {'id_chapitre' : id_chapitre}),
		success: function(data){
			$('#modexercicetype_partie').html(data.html);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});

/* TAGS
 * ==== */

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
});


/* GENERATION D'EXERCICE
 * ===================== */


/* Buttons
 * ======= */

var inputAnalyseButton = '<div class="btn-group interaction-btn"> <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"> <i class="icon-gear"></i> </button> <ul class="dropdown-menu" role="menu"> <li><a class="cursor cell-input-on">Input : on</a></li> <li><a class="cursor cell-input-off">Input : off</a></li> </ul> </div>';
var editCell0Button = '<div class="btn-group interaction-btn"> <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"> <i class="icon-gear"></i> <span class="caret"></span> </button> <ul class="dropdown-menu" role="menu"> <li><a class="cursor select-cell-type-case">Passer en cellule de type : case réponse</a></li> <li class="divider"></li> <li><a class="cursor remove-cell-row">Supprimer cette ligne</a></li> <li><a class="cursor remove-cell-col">Supprimer cette colonne</a></li> </ul> </div>';
var editCell1Button = '<div class="btn-group interaction-btn"> <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"> <i class="icon-gear"></i> <span class="caret"></span> </button> <ul class="dropdown-menu" role="menu"> <li><a class="cursor select-cell-type-textnmaths">Passer en cellule de type : text & maths</a></li> <li class="divider"></li> <li><a class="cursor remove-cell-row">Supprimer cette ligne</a></li> <li><a class="cursor remove-cell-col">Supprimer cette colonne</a></li> </ul> </div>';
var editTableauButton = '<div class="btn-group interaction-btn"> <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown"> <i class="icon-plus"></i> <span class="caret"></span> </button> <ul class="dropdown-menu" role="menu"> <li><a class="cursor add-tableau-row">Ajouter une ligne</a></li> <li><a class="cursor add-tableau-col">Ajouter une colonne</a></li> </ul> </div>';
var editTableauAnalyseButton = '<div class="btn-group interaction-btn"> <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown"> <i class="icon-plus"></i> <span class="caret"></span> </button> <ul class="dropdown-menu" role="menu"> <li><a class="cursor add-tableau-analyse-row-signes">Ajouter une ligne : "signes"</a></li> <li><a class="cursor add-tableau-analyse-row-variations">Ajouter une ligne : "variations"</a></li> <li><a class="cursor remove-tableau-analyse-row">Supprimer la dernière ligne</a></li> <li class="divider"></li> <li><a class="cursor add-tableau-analyse-col">Ajouter une valeur intermédiaire</a></li> <li><a class="cursor remove-tableau-analyse-col">Retirer une valeur intermédiaire</a></li> </ul> </div> <button type="button" rel="tooltip" data-title="Synchroniser" class="btn btn-greensea btn-xs interaction-btn synchroniser-tableau-analyse">Synchroniser</button>';
var addBriqueButton = '<div class="btn-group interaction-btn"> <button rel="tooltip" data-title="Insérer une nouvelle brique" type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu"> <!--<li class="dropdown-header">Construction énoncé</li>--> <li><a tabindex="-1" class="add-textnmaths cursor"><i class="icon-magic"></i> Texte & Maths</a></li> <li><a tabindex="-1" class="add-equations cursor"><i class="icon-superscript"></i> Equations</a></li> <li><a tabindex="-1" class="add-systeme cursor"><strong class="width-12 display-inline-block">{</strong> Systèmes</a></li> <li><a tabindex="-1" class="add-retour-ligne cursor"><i class="icon-mail-reply icon-flip-vertical"></i> Retour à la ligne</a></li><li><a tabindex="-1" class="add-saut-ligne cursor"><i class="icon-mail-reply-all icon-flip-vertical"></i> Saut de ligne</a></li> <li> <a tabindex="-1" data-toggle="modal" class="add-figure-graphe cursor"><i class="icon-bar-chart"></i> Figure / Graphe</a> </li> <!--<li><a tabindex="-1"><i class="icon-link"></i> Lien</a></li> <li><a tabindex="-1"><i class="icon-picture"></i> Image</a></li>--> <li> <a href="#add-case" tabindex="-1" data-toggle="modal" class="add-case cursor"><i class="icon-pencil"></i> Case</a> </li> <!--<li> <a href="#add-case-puissance" tabindex="-1" data-toggle="modal" class="add-case cursor"><i class="icon-pencil"></i> Case puissance</a> </li> <li> <a href="#add-case-indice" tabindex="-1" data-toggle="modal" class="add-case cursor"><i class="icon-pencil"></i> Case indice</a> </li>--> <li><a tabindex="-1" class="add-case-maths cursor"><span class="width-12 display-inline-block">∫</span> Case dans des maths</a></li> <li> <a tabindex="-1" class="add-liste cursor"><i class="icon-list-ul"></i> Liste</a> </li> <li> <a tabindex="-1" class="add-liste-ordonnee cursor"><i class="icon-list-ol"></i> Liste ordonnée</a> </li> <li> <a tabindex="-1" class="add-liste-deroulante cursor"><i class="icon-reorder"></i> Liste déroulante</a> </li> <li> <a tabindex="-1" data-toggle="modal" class="cursor add-radio"><i class="icon-circle-blank"></i> Créer un QCU</a> </li> <li> <a tabindex="-1" data-toggle="modal" class="cursor add-checkbox"><i class="icon-check"></i> Créer un QCM</a> </li> <li> <a tabindex="-1" data-toggle="modal" class="cursor add-vignettes"><i class="icon-list-alt"></i> Créer des vignettes</a> </li> <li> <a tabindex="-1" class="cursor add-tableau"><i class="icon-table"></i> Créer un tableau</a> </li> <li> <a tabindex="-1" class="cursor add-tableau-analyse"><i class="icon-table"></i>↗± Créer un tableau d\'analyse</a> </li> </ul> </div>';
var removeBriqueButton = '<button type="button" rel="tooltip" data-title="Supprimer cette brique" class="btn btn-danger btn-xs interaction-btn remove-brique"><i class="icon-remove"></i></button>';
var removeBriqueAndReponsesButton = '<button type="button" rel="tooltip" data-title="Supprimer cette brique-réponse" class="btn btn-danger btn-xs interaction-btn remove-brique-and-reponses"><i class="icon-remove"></i></button>';
var addChoice = '<button type="button" rel="tooltip" data-title="Ajouter un choix" class="btn btn-success btn-xs interaction-btn add-choice"><i class="icon-plus"></i></button>';
var removeChoice = '<button type="button" rel="tooltip" data-title="Enlever ce choix" class="btn btn-danger btn-xs interaction-btn delete-choice"><i class="icon-minus"></i></button>';
var addSuperBriqueButton = '<div class="btn-group interaction-btn"> <button rel="tooltip" data-title="Ajouter..." type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></button> <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu"> <li><a tabindex="-1" class="add-tutocours cursor">Ajouter un tutocours</a></li> <li><a tabindex="-1" class="add-entete cursor">Ajouter un entête</a></li> <li><a tabindex="-1" class="add-question cursor">Ajouter une question</a></li> </ul> </div>';
var removeSuperBriqueButton = '<button type="button" rel="tooltip" data-title="Supprimer cette question" class="btn btn-danger btn-xs interaction-btn remove-superbrique"><i class="icon-remove"></i></button>';
var addReponseButton = '<button rel="tooltip" data-title="Ajouter une réponse alternative" type="button" class="btn btn-xs btn-info add-reponse interaction-btn"><i class="icon-plus"></i></button>';
var removeReponseButton = '<button rel="tooltip" data-title="Supprimer cette réponse" type="button" class="btn btn-xs btn-danger remove-reponse interaction-btn"><i class="icon-remove"></i></button>';
var addBriquePlaceholder = '<div class="brique position-relative" data-brique-numero="0" data-brique-couche="0"><div class="add-brique" style="min-height:24px"></div></div>';
var removeBriquePlaceholder = '<div class="remove-brique" style="min-height:24px"></div>';
var addRemoveBriquePlaceholder = '<div class="add-remove-brique" style="min-height:24px"></div>';
var addSuperBriquePlaceholder = '<div class="add-superbrique" style="min-height:24px"></div>';
var removeSuperBriquePlaceholder = '<div class="remove-superbrique" style="min-height:24px"></div>';
var addRemoveSuperBriquePlaceholder = '<div class="add-remove-superbrique" style="min-height:24px"></div>';

$(document)
.on( { mouseenter: function() { $(this).append('<span class="position-absolute top-left">'+inputAnalyseButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'tr.tableau-analyse td')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute top-left">'+editCell0Button+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.edit-cell[data-cell-input="0"]')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute top-left">'+editCell1Button+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.edit-cell[data-cell-input="1"]')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute">'+addBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-brique')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute">'+removeBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.remove-brique')
.on( { mouseenter: function() { if( $(this).siblings('tr[data-reponse-numero="'+$(this).attr('data-reponse-numero')+'"]').length > 0){ $(this).children('td:eq(1)').append(' <span class="position-absolute pos-right">'+addReponseButton+' '+removeReponseButton+'</span>'); } else if ($(this).children('td:eq(1)').find('.mathquill-editable').length == 1) { $(this).children('td:eq(1)').append(' <span class="position-absolute pos-right">'+addReponseButton+'</span>'); } }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.reponse tr')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute up-18 display-inline-block min-width-50 min-height-28">'+removeBriqueButton+' '+addBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute up-18 display-inline-block min-width-50 min-height-28">'+removeBriqueAndReponsesButton+' '+addBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique-reponse')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute up-10 display-inline-block min-width-75 min-height-28">'+removeBriqueButton+' '+addBriqueButton+' '+addChoice+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique-add-choice')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute up-10 display-inline-block min-width-75 min-height-28">'+removeBriqueAndReponsesButton+' '+addBriqueButton+' '+addChoice+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique-reponse-add-choice')
.on( { mouseenter: function() { if ($(this).closest('div.complement').length>0) {$(this).append('<span class="position-absolute up-10 display-inline-block min-width-50 min-height-28">'+removeBriqueButton+' '+addBriqueButton+' '+editTableauButton+'</span>');} else {$(this).append('<span class="position-absolute up-10 display-inline-block min-width-50 min-height-28">'+removeBriqueAndReponsesButton+' '+addBriqueButton+' '+editTableauButton+'</span>');} }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique-reponse-edit-tableau')
.on( { mouseenter: function() { if ($(this).closest('div.complement').length>0) {$(this).append('<span class="position-absolute up-10 display-inline-block min-width-50 min-height-28">'+removeBriqueButton+' '+addBriqueButton+' '+editTableauAnalyseButton+'</span>');} else {$(this).append('<span class="position-absolute up-10 display-inline-block min-width-50 min-height-28">'+removeBriqueAndReponsesButton+' '+addBriqueButton+' '+editTableauAnalyseButton+'</span>');} }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-brique-reponse-edit-tableau-analyse')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute top-left display-inline-block">'+removeChoice+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.remove-choice')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute">'+addSuperBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-superbrique')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute">'+removeSuperBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.remove-superbrique')
.on( { mouseenter: function() { $(this).append('<span class="position-absolute display-inline-block min-width-50 min-height-35">'+removeSuperBriqueButton+' '+addSuperBriqueButton+'</span>'); }, mouseleave: function() { $(this).find('.interaction-btn').parent().remove(); } } , 'div.add-remove-superbrique')
.on( { mouseenter: function() { $(this).css('opacity',1); }, mouseleave: function() { $(this).css('opacity',0.5); } } , '.structure')
;


/* Adding content
 * ============== */

$(document)
// Ajouter une question
.on('click', ".add-question", function() {
	addSuperBrique($(this), 'question');
})

// Ajouter un tutocours
.on('click', ".add-tutocours", function() {
	addSuperBrique($(this), 'tutocours');
})

// Ajouter un entête
.on('click', ".add-entete", function() {
	addSuperBrique($(this), 'entete');
})

// Supprimer une question
.on('click', ".remove-superbrique", function() {
	var $this = $(this).closest('div.superbrique');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette question ?", 'Non', 'Oui', function(result) {			
		if (result) { 
			removeSuperBrique($this);
		}
	});
})

// Ajouter une réponse
.on('click', ".add-reponse", function() {
	addReponse($(this).closest('tr'));
})

// Supprimer une réponse
.on('click', ".remove-reponse", function() {
	removeReponse($(this).closest('tr'));
})

// Ajouter un textnmaths
.on('click', ".add-textnmaths", function() {
	addBrique($(this), 'textnmaths');
})

// Supprimer une brique
.on('click', ".remove-brique", function() {
	var $this = $(this);
	var thisBrique = $(this).closest('div.brique');
	var dansComplement = $this.closest('div.complement').length;
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette brique ?", 'Non', 'Oui', function(result) {			
		if (result) { 
			if ( dansComplement > 0 ) {
				removeBrique(thisBrique, 'complement');
			}
			else {
				removeBrique(thisBrique, 'superbrique');
			}
		}
	});
})

// Supprimer une brique avec réponse
.on('click', ".remove-brique-and-reponses", function() {
	var $this = $(this).closest('div.brique');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette brique ainsi que toutes les réponses liées ?", 'Non', 'Oui', function(result) {
		if (result) { removeBriqueAndReponses($this); }	
	});
})

// Ajouter un retour à la ligne
.on('click', ".add-retour-ligne", function() {
	addBrique($(this), 'retour_ligne');
})

// Ajouter un saut de ligne
.on('click', ".add-saut-ligne", function() {
	addBrique($(this), 'saut_ligne');
})

// =========================== //

// Ajout en cours d'une case
.on('click', ".add-case", function() {
	$(this).closest('div.brique').addClass('adding-case');
})
.on('click', ".cancel-case", function() {
	$('.adding-case').removeClass('adding-case');
})

// Ajouter une case
.on('click', ".add-case-select", function() {
	addBriqueAndReponse($('.adding-case'), 'case', $(this).attr('clavier'));
	$('.adding-case').removeClass('adding-case');
})

// =========================== //

// Ajouter une case maths
.on('click', ".add-case-maths", function() {
	$(this).closest('div.brique').after('<div class="temp-brique"><button type="button" class="btn btn-danger btn-xs remove-case-maths"><i class="icon-remove"></i></button> <button class="btn btn-success btn-xs add-case-maths-normale">Grande case-réponse</button> <button class="btn btn-success btn-xs add-case-maths-petite">Petite case-réponse</button> <button class="btn btn-greensea btn-xs validate-case-maths">Terminer et insérer</button><br><span class="mathquill-editable no-sync mathquill-update"></span></div>');
	$('.mathquill-update').mathquill('editable').first().find('textarea').focus();
	$('.mathquill-update').removeClass('mathquill-update');
	// addBrique($(this).closest('div.brique'), 'case maths', 1);
})
.on('click', ".remove-case-maths", function() {
	var $this = $(this).parent();
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer cette brique ?", 'Non', 'Oui', function(result) {
		if (result) { $this.fadeOut(function(){ $(this).remove(); }); }	
	});
	
})
.on('click', ".validate-case-maths", function() {
	var $this = $(this);
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous terminer l\'édition de cette formule et passer à l\'édition des réponses ?", 'Non', 'Oui', function(result) {
		if (result) { 
			var latex = $this.nextAll('.mathquill-editable').mathquill('latex');
			var repNum = latex.split("⬜").length + latex.split("◻").length - 2;
			/////
			$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				'border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} });
			var existingSuperbrique  = new SuperBrique( $this.closest('div.superbrique') );
			var existingBrique       = new Brique( $this.closest('div.temp-brique').prevAll('div.brique').first() );
			latex = latex.replace(/⬜/g,"\\editable{}").replace(/◻/g,"\\smalleditable{}");
			var contenu = latex;
			
			$.ajax({
				type: "POST",
				data: { 'contenu' : contenu },
				url: Routing.generate("majordesk_app_editor_add_brique_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'numero' : parseInt(existingBrique.numero) + 1, 'type' : 'case maths', 'couche' : existingBrique.couche }),
				success: function(data){	
					var numero = parseInt(existingBrique.numero) + 1;
					var content = '<div class="brique position-relative display-inline-block vertical-align-middle" data-brique-id="'+data.id_brique+'" data-brique-numero="'+numero+'" data-brique-type="case maths" data-brique-couche="'+existingBrique.couche+'"> ';
					content = content + '<div class="add-remove-brique-reponse">';
					content = content + '<span class="mathquill-embedded-latex mathquill-update">'+latex+'</span>';
					content = content + '</div> </div>';
					existingBrique.increment(1);
					existingBrique.selector.after(content);
					var precedingInputs = existingBrique.getPrecedingInputsNumberIncluding();
					var constructor = {};
					for(var j=1;j<=repNum;j++) {
						constructor[j] = {'contenu':'', 'type':'expression exacte'};
					}
					$.ajax({
						type: "POST",
						data: { 'constructor' : JSON.stringify(constructor) },
						url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : data.id_brique, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : 2 }),
						success: function(data2) {	
							var newReponse;
							var indx = 1;
							jQuery.each(data2.ids, function(i, val) {
								newReponse = new NewReponse(existingSuperbrique.id, data.id_brique, val, precedingInputs + 1, 2, constructor[indx]['contenu'], constructor[indx]['type'], existingSuperbrique.getCurrentDependances());
								existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
								if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
									existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
								} else {
									existingSuperbrique.getTbodySelector().prepend(newReponse.content);
								}
								precedingInputs++;
								indx++;
							});		
							$this.parent().remove();
							$('.mathquill-update').mathquill();
							$('.mathquill-update').removeClass('mathquill-update');
							$('.mathquill-update-reponse').mathquill('editable').find('textarea').focus();
							$('.mathquill-update-reponse').removeClass('mathquill-update-reponse');							
							$.unblockUI();
						},
						error: function() {
							$.unblockUI();
							alert('La requête n\'a pas abouti');
						}
					});
				},
				error: function() {
					$.unblockUI();
					alert('La requête n\'a pas abouti');
				}
			});	
		}	
	});
	
	
})
.on('click', ".add-case-maths-normale", function() {
	$(this).nextAll('.mathquill-editable').mathquill('write', '⬜').find('textarea').focus();
})
.on('click', ".add-case-maths-petite", function() {
	$(this).nextAll('.mathquill-editable').mathquill('write', '◻').find('textarea').focus();
})

// =========================== //

// Ajouter une résolution d'équations
.on('click', ".add-equations", function() {
	addBrique($(this).closest('div.brique'), 'equations', 0);
})

// =========================== //

// Ajouter un système d'équations
.on('click', ".add-systeme", function() {
	addBrique($(this).closest('div.brique'), 'systeme', 0);
})

// =========================== //

// Ajouter une liste
.on('click', ".add-liste", function() {
	addBrique($(this).closest('div.brique'), 'liste', 0);
})

// Ajouter une liste ordonnée
.on('click', ".add-liste-ordonnee", function() {
	addBrique($(this).closest('div.brique'), 'liste ordonnee', 0);
})

// =========================== //

// Ajouter une liste déroulante
.on('click', ".add-liste-deroulante", function() {
	addBriqueAndReponse($(this).closest('div.brique'), 'liste deroulante', 4);
})

// =========================== //

// Ajouter un radio
.on('click', ".add-radio", function() {
	addBriqueAndReponse($(this).closest('div.brique'), 'radio', 4);
})

// =========================== //

// Ajouter un checkbox
.on('click', ".add-checkbox", function() {
	addBriqueAndReponse($(this).closest('div.brique'), 'checkbox', 0);
})

// =========================== //

// Ajouter un groupe de vignettes
.on('click', ".add-vignettes", function() {
	addBriqueAndReponse($(this).closest('div.brique'), 'vignettes', 4);
})

// =========================== //

// Ajouter un tableau
.on('click', ".add-tableau", function() {
	addBrique($(this).closest('div.brique'), 'tableau', 0);
})

// =========================== //

// Ajouter un tableau
.on('click', ".add-tableau-analyse", function() {
	addBrique($(this).closest('div.brique'), 'tableau analyse', 0);
})

// =========================== //

// Ajouter un(e) figure/graphe
.on('click', ".add-figure-graphe", function() {
	addBrique($(this).closest('div.brique'), 'figure graphe', 0);
})

// =========================== //



/* Functions
 * ========= */

function tickReponse(thisSelector) {
	var existingReponse = new Reponse( thisSelector.closest('tr') );
	var checkReponse    = new CheckReponse(existingReponse, thisSelector);
	var existingSuperbrique= new SuperBrique( thisSelector.closest('div.superbrique') );
			
	if ( checkReponse.selector.is(':checked') )
	{
		if ( checkReponse.getCheckedInColumn() >= 2)
		{
			var dependances = {};
			var k = 1;
			thisSelector.closest('tbody').find('input[data-check-numero="' + checkReponse.numero + '"]:checked').each(function() {
				dependances[k] = $(this).closest('tr').attr('data-reponse-id');
				k++;
			});

			$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				'border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} });
			$.ajax({
				type: "POST",
				data: { 'dependances' : JSON.stringify(dependances) },
				url: Routing.generate("majordesk_app_editor_merge_reponses_to_mapping", {'id_superbrique' : existingSuperbrique.id }),
				success: function() {	
					$.unblockUI();
				},
				error: function() {
					$.unblockUI();
					alert('La requête n\'a pas abouti');
				}
			});
		}
		if ( checkReponse.getCheckedInColumn() == 2 && checkReponse.selector.is(':last-child'))
		{
			checkReponse.addColumn();
		}
	}
	else
	{
		if ( checkReponse.getCheckedInColumn() >= 1)
		{
			$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				'border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} });
			$.ajax({
				type: "POST",
				url: Routing.generate("majordesk_app_editor_unmerge_reponse_from_mapping", {'id_superbrique' : existingSuperbrique.id, 'id_reponse' : existingReponse.id }),
				success: function() {	
					$.unblockUI();
				},
				error: function() {
					$.unblockUI();
					alert('La requête n\'a pas abouti');
				}
			});
		}
		if ( checkReponse.getCheckedInColumn() == 1)
		{
			checkReponse.removeColumn();
		}
		if ( checkReponse.getCheckedInColumn() == 0 && !thisSelector.is(':last-child') )
		{
			checkReponse.removeColumn();
		}			
	}
}

/* Synchronisation
 * =============== */

 var b4Sync = '';

$(document).on('focus', '.mathquill-editable', function() {
	b4Sync = $(this).mathquill('latex');
})
// Geogebra
.on('click', '.update-geogebra', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( $(this).closest('div.brique') );
	// var contenu = $(this).next().attr('data-param-ggbbase64');
	// alert('contenu : '+parameters.ggbBase64);
	$.ajax({
		type: "POST",
		// dataType: "json",
		// data: { 'contenu' : ggbApplet.getBase64() },
		data: { 'contenu' : document.ggbApplet.getBase64() },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
// Mathquill
.on('blur', '.mathquill-editable', function() {
	$('.editing').removeClass('editing');
	if (usingSlideOutKeyboard) {
		var $this = $(this);
		$this.addClass('editing');
		setTimeout(function() { $this.find('textarea').focus(); }, 0);
	}
	else if ($(this).hasClass('no-sync')) {
		// do nothing
	}
	else {
		if ($(this).mathquill('latex') != b4Sync) {
			$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				'border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} });
			if ($(this).hasClass('is-reponse')) {
				var existingReponse= new Reponse( $(this).closest('tr') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : $(this).mathquill('latex') },
					url: Routing.generate("majordesk_app_editor_update_reponse_contenu", {'id_reponse' : existingReponse.id }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-multiple')) { 
				var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
				contenuArr[$(this).attr('data-key')].contenu = $(this).mathquill('latex');
				$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-cell')) { 
				var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
				var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')];
				rowArr[$(this).closest('td').attr('data-cell-col')].contenu = $(this).mathquill('latex');
				$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-cell-analyse')) { 
				var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
				var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
				rowArr[$(this).closest('td').attr('data-cell-col')].contenu = $(this).mathquill('latex');
				$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-cell-borneg')) { 
				var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
				var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
				rowArr[$(this).closest('td').attr('data-cell-col')].borneg = $(this).mathquill('latex');
				$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-cell-borned')) { 
				var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
				var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
				rowArr[$(this).closest('td').attr('data-cell-col')].borned = $(this).mathquill('latex');
				$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			} else if ($(this).hasClass('is-editable')) { 
				var existingBrique= new Brique( $(this).closest('div.brique') );
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'contenu' : $(this).mathquill('latex') },
					url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
					success: function(){
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
					}
				});
			}
		}
	}
})
.on('change', '.reponse-contenu-select', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingReponse= new Reponse( $(this).closest('tr') );
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : $(this).val() },
		url: Routing.generate("majordesk_app_editor_update_reponse_contenu", {'id_reponse' : existingReponse.id }),
		success: function(){
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('change', '.reponse-type-select', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingReponse= new Reponse( $(this).closest('tr') );
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'type' : $(this).val() },
		url: Routing.generate("majordesk_app_editor_update_reponse_type", {'id_reponse' : existingReponse.id }),
		success: function(){
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})

// Synchronisation tableau d'analyse
.on('click', '.synchroniser-tableau-analyse', function(){
	console.log('synchronisation...');
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( $(this).closest('div.brique') );
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.cell-input-on', function(e){
	e.stopPropagation();
	if ($(this).closest('div.complement').length==0) {
		if ( ($(this).closest('div.brique').attr('data-brique-contenu').match(/"input":"1"/g) || []).length == 0 ) {
			var existingSuperbrique  = new SuperBrique( $(this).closest('div.superbrique') );
			var existingBrique       = new Brique( $(this).closest('div.brique') );
			var precedingInputs = existingBrique.getPrecedingInputsNumberIncluding();
			var constructor = { 1:{'contenu':'', 'type':'tableau analyse'} };
			$.ajax({
				type: "POST",
				data: { 'constructor' : JSON.stringify(constructor) },
				url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : existingBrique.id, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : 2 }),
				success: function(data2) {	
					var newReponse;
					var indx = 1;
					jQuery.each(data2.ids, function(i, val) {
						newReponse = new NewReponse(existingSuperbrique.id, existingBrique.id, val, precedingInputs + 1, 2, constructor[indx]['contenu'], constructor[indx]['type'], existingSuperbrique.getCurrentDependances());
						existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
						if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
							existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
						} else {
							existingSuperbrique.getTbodySelector().prepend(newReponse.content);
						}
						precedingInputs++;
						indx++;
					});								
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});

			//add is-input
			$(this).closest('div.brique').prepend('<div class="is-input"></div>');
		}
		var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
		var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
		rowArr[$(this).closest('td').attr('data-cell-col')].input = "1";
		$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
		$(this).closest('td').css('background-color','#d8f1f5');
		
		//synchronisation
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( $(this).closest('div.brique') );
		$.ajax({
			type: "POST",
			dataType: "json",
			data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
			url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
			}
		});
	} else {
		alert('Impossible d\'insérer une case réponse dans une correction ou un indice');
	}
})
.on('click', '.cell-input-off', function(e){
	e.stopPropagation();
	if ( $(this).closest('div.complement').length == 0 ) {
		if ( ($(this).closest('div.brique').attr('data-brique-contenu').match(/"input":"1"/g) || []).length == 1 ) {
			removeReponse($('tr[data-brique-id="'+$(this).closest('div.brique').attr('data-brique-id')+'"]'));
			//remove is-input
			$(this).closest('div.brique').find('.is-input').remove();
		}
		var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
		var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
		rowArr[$(this).closest('td').attr('data-cell-col')].input = "0";
		$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
		$(this).closest('td').css('background-color','#ffffff');
		//synchronisation
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( $(this).closest('div.brique') );
		$.ajax({
			type: "POST",
			dataType: "json",
			data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
			url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
			}
		});
	} else {
		alert('Action impossible');
	}
})
.on('click', '.add-tableau-analyse-row-signes', function(){
	var thisTbody = $(this).closest('div.brique').find('table tbody');
	thisTbody.find('tr:last').css('border-bottom','1px solid black');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	var signesHtml = '<tr data-cell-row="'+(Object.keys(contenuArr).length+1)+'" data-type="signe" class="tableau-analyse">';
	var signesRow = {};
	signesRow[1] = {"input":"0","contenu":""};
	signesHtml = signesHtml + '<td data-cell-col="1" class="col-lg-2 position-relative" style="border-right:1px solid black"><div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div></td>';
	for(var j=2;j<=Object.keys(contenuArr[1].contenu).length;j++) {
		if (j % 2 == 0) {
			signesRow[j] = {"input":"0","contenu":""};
			signesHtml = signesHtml + '<td data-cell-col="'+j+'" class="cursor position-relative"><div class="text-center"></div></td>';
		} else {
			signesRow[j] = {"input":"0","contenu":"+"};
			signesHtml = signesHtml + '<td data-cell-col="'+j+'" class="cursor position-relative"><div class="text-center">+</div></td>';
		}
	}
	signesHtml = signesHtml + '</td>';
	contenuArr[Object.keys(contenuArr).length+1] = { "type":"signe", "contenu":signesRow };
	thisTbody.append(signesHtml);
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	$('.mathquill-update').mathquill('editable');
	$('.mathquill-update').removeClass('mathquill-update');
})
.on('click', '.add-tableau-analyse-row-variations', function(){
	var thisTbody = $(this).closest('div.brique').find('table tbody');
	thisTbody.find('tr:last').css('border-bottom','1px solid black');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
		if (Object.keys(contenuArr[1].contenu).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}
	var variationsHtml = '<tr data-cell-row="'+(Object.keys(contenuArr).length+1)+'" style="position:relative;top:'+decalageMilieu+'" data-type="variations" class="tableau-analyse">';
	var variationsRow = {};
	variationsRow[1] = {"input":"0","contenu":"","position":"milieu"};
	variationsHtml = variationsHtml + '<td data-cell-col="1" class="col-lg-2 position-relative" style="border-right:1px solid black"><div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div></td>';
	for(var j=2;j<=Object.keys(contenuArr[1].contenu).length;j++) {
		if (j % 2 == 0) {
			variationsRow[j] = {"input":"0","contenu":"%vide%","position":"haut","positiong":"haut","positiond":"haut","borneg":"","borned":""};
			variationsHtml = variationsHtml + '<td data-cell-col="'+j+'" class="cursor position-relative" style="padding-left:0;padding-right:0"><div class="text-center"></div></td>';
		} else {
			variationsRow[j] = {"input":"0","contenu":"%asc%","position":"milieu"};
			if (flecheType == 1) {
				variationsHtml = variationsHtml + '<td data-cell-col="'+j+'" class="cursor position-relative" style="padding-left:0;padding-right:0"><div class="text-center"><img src="../img/maths/asc-1.png"/></div></td>';
			} else {
				variationsHtml = variationsHtml + '<td data-cell-col="'+j+'" class="cursor position-relative" style="padding-left:0;padding-right:0"><div class="text-center"><img src="../img/maths/asc-milieu-'+flecheType+'.png"/></div></td>';
			}
		}
	}
	variationsHtml = variationsHtml + '</td>';
	contenuArr[Object.keys(contenuArr).length+1] = { "type":"variations", "contenu":variationsRow };
	thisTbody.append(variationsHtml);
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	$('.mathquill-update').mathquill('editable');
	$('.mathquill-update').removeClass('mathquill-update');
})
.on('click', '.remove-tableau-analyse-row', function(){
	var thisTbody = $(this).closest('div.brique').find('table tbody');
	thisTbody.find('tr:last').fadeOut(function(){ $(this).remove(); thisTbody.find('tr:last').css('border-bottom',0); });	
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	delete contenuArr[Object.keys(contenuArr).length];
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	if ( ($(this).closest('div.brique').attr('data-brique-contenu').match(/"input":"1"/g) || []).length == 0 ) {
		removeReponse($('tr[data-brique-id="'+$(this).closest('div.brique').attr('data-brique-id')+'"]'));
		//remove is-input
		$(this).closest('div.brique').find('.is-input').remove();
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( $(this).closest('div.brique') );
		$.ajax({
			type: "POST",
			dataType: "json",
			data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
			url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
			}
		});
	}
})
.on('click', '.add-tableau-analyse-col', function(){
	var thisBrique = $(this).closest('div.brique');
	var thisTbody = thisBrique.find('table tbody');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
		if (Object.keys(contenuArr[1].contenu).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 6) { var flecheType = 2; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 8) { var flecheType = 3; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else if (Object.keys(contenuArr[1].contenu).length >= 10) { var flecheType = 3; var decalageBas = '40px'; var decalageMilieu = '20px'; }
	var rowCount = Object.keys(contenuArr).length;
	thisTbody.find('img').each(function(){ // changement des flèches
		if (flecheType==1) {
			$(this).attr('src', $(this).attr('src').replace(/([0-9])/,function(match,number){return "milieu-"+(+number+1);}));
		} else {
			$(this).attr('src', $(this).attr('src').replace(/([0-9])/,function(match,number){return +number+1;}));
		}
	});
	for(var i=1;i<=rowCount;i++) {
		if (contenuArr[i].type == "entete") {
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+1)+'" class="cursor position-relative"><div class="text-center"></div></td>');
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+2)+'" class="cursor position-relative"><div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div></td>');
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":""};
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":""};			
		} else if (contenuArr[i].type == "signe") {
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+1)+'" class="cursor position-relative"><div class="text-center"></div></td>');
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+2)+'" class="cursor position-relative"><div class="text-center">+</div></td>');
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":""};
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":"+"};
		} else if (contenuArr[i].type == "variations") {
			if (contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].contenu == "%bd-interdite%") {
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].contenu = "%valeur-interdite%";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].position = "haut";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].positiong = "haut";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].positiond = "haut";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].borneg = "";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].borned = "";
				thisTbody.find('tr:eq('+(i-1)+')').find('td:last').html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				thisTbody.find('tr:eq('+(i-1)+')').find('td:last').css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','center').css('vertical-align','center');
				if (flecheType > 2) { thisTbody.find('tr:eq('+(i-1)+')').find('td:last').css('font-size','10px'); }
			}
			for(var j=1;j<=Object.keys(contenuArr[i].contenu).length;j++) {
				if (j%2==0) {
					if (contenuArr[i].contenu[j].position == "milieu") {
						thisTbody.find('tr:eq('+(i-1)+')').find('td:eq('+(j-1)+')').find('div.text-center').css('top',decalageMilieu);
					} else if (contenuArr[i].contenu[j].position == "bas") {
						thisTbody.find('tr:eq('+(i-1)+')').find('td:eq('+(j-1)+')').find('div.text-center').css('top',decalageBas);
					}
				}
			}
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+1)+'" class="cursor position-relative"><div class="text-center"><img src="../img/maths/asc-milieu-'+(flecheType+1)+'.png"/></div></td>');
			thisTbody.find('tr:eq('+(i-1)+')').append('<td data-cell-col="'+(Object.keys(contenuArr[i].contenu).length+2)+'" class="cursor position-relative"><div class="text-center"></div></td>');
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":"%asc%","position":"milieu"};
			contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length+1] = {"input":"0","contenu":"%vide%","position":"haut","positiong":"haut","positiond":"haut","borneg":"","borned":""};
		} else {
			console.log("une erreur est survenue");
		}
	}
	thisBrique.attr('data-brique-contenu', JSON.stringify(contenuArr));
	$('.mathquill-update').mathquill('editable');
	$('.mathquill-update').removeClass('mathquill-update');
})
.on('click', '.remove-tableau-analyse-col', function(){
	var thisBrique = $(this).closest('div.brique');
	var thisTbody = thisBrique.find('table tbody');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
		if (Object.keys(contenuArr[1].contenu).length <= 4) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 8) { var flecheType = 3; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(contenuArr[1].contenu).length == 10) { var flecheType = 4; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(contenuArr[1].contenu).length >= 12) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
	var rowCount = Object.keys(contenuArr).length;
	thisTbody.find('img').each(function(){ // changement des flèches
		if (flecheType<=2) {
			$(this).attr('src', $(this).attr('src').replace(/([a-z]+-[0-9])/,function(match,number){return +number-1;}));
		} else {
			$(this).attr('src', $(this).attr('src').replace(/([0-9])/,function(match,number){return +number-1;}));
		}
	});
	for(var i=1;i<=rowCount;i++) {
		if (contenuArr[i].type == "entete" || contenuArr[i].type == "signe") {
			thisTbody.find('tr:eq('+(i-1)+')').find('td:last').remove();
			thisTbody.find('tr:eq('+(i-1)+')').find('td:last').remove();
			delete contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length];
			delete contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length];			
		} else if (contenuArr[i].type == "variations") {
			for(var j=1;j<=Object.keys(contenuArr[i].contenu).length;j++) {
				if (j%2==0) {
					if (contenuArr[i].contenu[j].position == "milieu") {
						thisTbody.find('tr:eq('+(i-1)+')').find('td:eq('+(j-1)+')').find('div.text-center').css('top',decalageMilieu);
					} else if (contenuArr[i].contenu[j].position == "bas") {
						thisTbody.find('tr:eq('+(i-1)+')').find('td:eq('+(j-1)+')').find('div.text-center').css('top',decalageBas);
					}
				}
			}
			thisTbody.find('tr:eq('+(i-1)+')').find('td:last').remove();
			thisTbody.find('tr:eq('+(i-1)+')').find('td:last').remove();
			delete contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length];
			delete contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length];
			if (contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].contenu == "%valeur-interdite%") {
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].contenu = "%bd-interdite%";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].position = "haut";
				// contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].positiong = "haut";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].positiond = "haut";
				// contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].borneg = "";
				contenuArr[i].contenu[Object.keys(contenuArr[i].contenu).length].borned = "";
				thisTbody.find('tr:eq('+(i-1)+')').find('td:last').html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:8px;"></div></div>');
				thisTbody.find('tr:eq('+(i-1)+')').find('td:last').css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','right').css('vertical-align','center');
				if (flecheType > 2) { thisTbody.find('tr:eq('+(i-1)+')').find('td:last').css('font-size','10px'); }
			}
		} else {
			console.log("une erreur est survenue");
		}
	}
	thisBrique.attr('data-brique-contenu', JSON.stringify(contenuArr));
	$('.mathquill-update').mathquill('editable');
	$('.mathquill-update').removeClass('mathquill-update');
	if ( (thisBrique.attr('data-brique-contenu').match(/"input":"1"/g) || []).length == 0 ) {
		removeReponse($('tr[data-brique-id="'+thisBrique.attr('data-brique-id')+'"]'));
		//remove is-input
		$(this).closest('div.brique').find('.is-input').remove();
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( thisBrique );
		$.ajax({
			type: "POST",
			dataType: "json",
			data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
			url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
			}
		});
	}
})
.on('click', 'tr.tableau-analyse[data-type="signe"] td:not(:first-child)', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	var rowArr = contenuArr[row].contenu;
	if (col % 2 == 0) {
		if (rowArr[col].contenu == "") {
			rowArr[col].contenu = "%valeur-nulle%";
			$(this).html('<div class="text-center">0</div>');
			$(this).css('height','100%').css('background-image','url("../img/maths/valeur-nulle.png")').css('background-repeat','repeat-y').css('background-position','center').css('vertical-align','center');
		} else if (rowArr[col].contenu == "%valeur-nulle%") {
			rowArr[col].contenu = "%valeur-interdite%";
			$(this).html('<div class="text-center"></div>');
			$(this).css('height','100%').css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','center').css('vertical-align','center');
		} else if (rowArr[col].contenu == "%valeur-interdite%") {
			rowArr[col].contenu = "";
			$(this).css('height','auto').css('background-image','none');
		}
	} else {
		if (rowArr[col].contenu == "+") {
			rowArr[col].contenu = "-";
			$(this).html('<div class="text-center">-</div>');
		} else if (rowArr[col].contenu == "-") {
			rowArr[col].contenu = "+";
			$(this).html('<div class="text-center">+</div>');
		}
	}
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
})
.on('click', 'tr.tableau-analyse[data-type="variations"] td:not(:first-child)', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	var rowArr = contenuArr[row].contenu;
		if (Object.keys(rowArr).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(rowArr).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}
	if (col % 2 == 0) {
		if (col == 2) {
			if (rowArr[col].contenu == "%bg-interdite%" && rowArr[col].position == "bas") {
				rowArr[col].contenu = "%vide%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('');
				$(this).css('height','auto').css('padding','8px').css('background-image','none');
				if (flecheType > 2) { $(this).css('font-size','14px'); }
			} else if (rowArr[col].contenu == "%vide%") {
				rowArr[col].contenu = "";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].contenu == "%bg-interdite%" && rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "bas") {
				rowArr[col].contenu = "%bg-interdite%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','left').css('vertical-align','center');
				if (flecheType > 2) { $(this).css('font-size','10px'); }
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			}
		} else if (col == Object.keys(rowArr).length) {
			if (rowArr[col].contenu == "%bd-interdite%" && rowArr[col].position == "bas") {
				rowArr[col].contenu = "%vide%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('');
				$(this).css('height','auto').css('padding','8px').css('background-image','none');
				if (flecheType > 2) { $(this).css('font-size','14px'); }
			} else if (rowArr[col].contenu == "%vide%") {
				rowArr[col].contenu = "";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].contenu == "%bd-interdite%" && rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:8px;"></div></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "bas") {
				rowArr[col].contenu = "%bd-interdite%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:8px;"></div></div>');
				$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','right').css('vertical-align','center');
				if (flecheType > 2) { $(this).css('font-size','10px'); }
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			}
		} else {
			if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "bas" && rowArr[col].positiond == "bas") {
				rowArr[col].contenu = "%vide%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('');
				$(this).css('height','auto').css('padding','8px').css('background-image','none');
				$(this).css('font-size','14px');
			} else if (rowArr[col].contenu == "%vide%") {
				rowArr[col].contenu = "";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "haut" && rowArr[col].positiond == "haut") {
				rowArr[col].positiond = "bas";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "haut" && rowArr[col].positiond == "bas") {
				rowArr[col].positiong = "bas";
				rowArr[col].positiond = "haut";
				rowArr[col].position = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "bas" && rowArr[col].positiond == "haut") {
				rowArr[col].positiong = "bas";
				rowArr[col].positiond = "bas";
				rowArr[col].position = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "haut") {
				rowArr[col].position = "milieu";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageMilieu+'"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "milieu") {
				rowArr[col].position = "bas";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update is-cell-analyse"></span></div>');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			} else if (rowArr[col].position == "bas") {
				rowArr[col].contenu = "%valeur-interdite%";
				rowArr[col].position = "haut";
				rowArr[col].positiong = "haut";
				rowArr[col].positiond = "haut";
				rowArr[col].borneg = "";
				rowArr[col].borned = "";
				$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update is-cell-borned"></span></div></div>');
				$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','center').css('font-size','10px');
				$('.mathquill-update').mathquill('editable');
				$('.mathquill-update').removeClass('mathquill-update');
			}
		}
	} else {
		if (flecheType!=1) {
			if (rowArr[col].contenu == "%asc%" && rowArr[col].position == "milieu") {
				rowArr[col].position = "haut";
				$(this).find('img').attr('src','../img/maths/asc-haut-'+flecheType+'.png');
			} else if (rowArr[col].contenu == "%asc%" && rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				$(this).find('img').attr('src','../img/maths/asc-bas-'+flecheType+'.png');
			} else if (rowArr[col].contenu == "%asc%" && rowArr[col].position == "bas") {
				rowArr[col].contenu = "%desc%";
				rowArr[col].position = "milieu";
				$(this).find('img').attr('src','../img/maths/desc-milieu-'+flecheType+'.png');
			} else if (rowArr[col].contenu == "%desc%" && rowArr[col].position == "milieu") {
				rowArr[col].position = "haut";
				$(this).find('img').attr('src','../img/maths/desc-haut-'+flecheType+'.png');
			} else if (rowArr[col].contenu == "%desc%" && rowArr[col].position == "haut") {
				rowArr[col].position = "bas";
				$(this).find('img').attr('src','../img/maths/desc-bas-'+flecheType+'.png');
			} else if (rowArr[col].contenu == "%desc%" && rowArr[col].position == "bas") {
				rowArr[col].contenu = "%asc%";
				rowArr[col].position = "milieu";
				$(this).find('img').attr('src','../img/maths/asc-milieu-'+flecheType+'.png');
			}
		} else {
			if (rowArr[col].contenu == "%asc%") {
				rowArr[col].contenu = "%desc%";
				rowArr[col].position = "milieu";
				$(this).find('img').attr('src','../img/maths/desc-1.png');
			} else if (rowArr[col].contenu == "%desc%") {
				rowArr[col].contenu = "%asc%";
				rowArr[col].position = "milieu";
				$(this).find('img').attr('src','../img/maths/asc-1.png');
			}
		}
	}
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
})
;


/* Objects & Fonctions
 * =================== */

/////////////////
// SUPERBRIQUE //
/////////////////

function SuperBrique(selector) {			
	this.selector = selector,
	this.numero = parseInt(selector.attr('data-superbrique-numero')),
	this.id = parseInt(selector.attr('data-superbrique-id'))
	this.type = parseInt(selector.attr('data-superbrique-type'))
	
	this.getCurrentDependances = function() {
		var dependancesNumber = parseInt(this.selector.children('div.reponse').find('tbody tr:first-child td:last-child').children().length);
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
	
	this.getReponseSelectorByNumero  = function(numero) {
		return this.selector.children('div.reponse').find('tr[data-reponse-numero="' + numero + '"]').last();			
	}
	
	this.getTbodySelector = function() {
		return this.selector.children('div.reponse').find('tbody');
	}
	
	this.incrementAllReponseAbove = function(numero, incr) {
		this.getTbodySelector().find('tr[data-reponse-numero]').each( function() {
			if ( $(this).attr('data-reponse-numero') > numero ) {
				var newNumeroReponse = parseInt($(this).attr('data-reponse-numero')) + incr;
				$(this).attr('data-reponse-numero', newNumeroReponse);
				$(this).find('td:first-child strong').html(newNumeroReponse);
			}
		});
	}
	
	this.increment = function(incr) {
		var numero = this.numero;
		this.selector.siblings('[data-superbrique-numero]').each( function() {
			if ( $(this).attr('data-superbrique-numero') > numero ) {
				$(this).attr('data-superbrique-numero', parseInt($(this).attr('data-superbrique-numero')) + incr);
				// AJAX
			}
		});
	}
}

function NewSuperBrique(id, numero, type, id_indice, id_correction) {	
	this.id = id,		
	this.numero = numero,
	this.type = type;
	if (type == 'question') {
		this.content = '<div class="superbrique question" data-superbrique-id="'+this.id+'" data-superbrique-numero="'+this.numero+'" data-superbrique-type="'+this.type+'"><div class="col-xs-12 col-lg-1"><i class="icon-remove icon-large text-light-grey" rel="tooltip" data-title="Etat"></i> <span class="badge" rel="tooltip" data-title="Nombre d\'essai(s)">0</span> '+addRemoveSuperBriquePlaceholder+' <span class="visible-xs"><br><br></span></div><div class="col-lg-6">'+addBriquePlaceholder+'<span class="pull-right"><br><button rel="tooltip" data-title="Valider" class="btn btn-mini btn-success" type="button" disabled>Valider <i class="icon-ok text-white"></i></button></span></div><div class="col-lg-5"><div class="complement indice" data-complement-id="'+id_indice+'" data-complement-numero="1" data-complement-type="indice"><div class="alert alert-warning"><i class="icon icon-info-sign indice"></i> <strong>Indice : </strong> <br><br>'+addBriquePlaceholder+'</div></div><div class="complement correction" data-complement-id="'+id_correction+'" data-complement-numero="0" data-complement-type="correction"><div class="alert alert-success"><i class="icon icon-ok-sign correction"></i> <strong>Correction : </strong> <br><br>'+addBriquePlaceholder+'</div></div></div><div class="reponse"> <br> <div class="col-md-offset-1 col-lg-10 well"> <div class="table-responsive"> <table class="table"> <thead> <tr> <th>#</th> <th>Réponse</th> <th>Type de réponse</th> <th>Dépendances</th> </tr> </thead> <tbody><tr> <td> </td>  <td> </td>  <td> <strong>Type de dépendance :</strong><br><br> Association <br>Association par groupe<br> Permutation totale </td>  <td class="dependance_td"> <div class="pull-left"> <br><br> - <br> - <br> - </div> </td> </tr></tbody> </table> </div> </div> <div class="clearfix"></div> <br> </div>';
	} else if (type == 'entete') {
		this.content = '<div class="superbrique entete" data-superbrique-id="'+this.id+'" data-superbrique-numero="'+this.numero+'" data-superbrique-type="'+this.type+'"><div class="col-xs-12 col-lg-1"><i rel="tooltip" data-title="Entête" class="icon-info icon-large text-light-grey"></i>'+addRemoveSuperBriquePlaceholder+' <span class="visible-xs"><br><br></span></div><div class="col-lg-6">'+addBriquePlaceholder+'</div><div class="col-lg-5"></div><div class="clearfix"></div>';
	} else if (type == 'tutocours') {
		this.content = '<div class="superbrique tutocours" data-superbrique-id="'+this.id+'" data-superbrique-numero="'+this.numero+'" data-superbrique-type="'+this.type+'"><div class="col-xs-12 col-lg-1"><i rel="tooltip" data-title="Tutocours" class="icon-compass icon-large text-light-grey"></i>'+addRemoveSuperBriquePlaceholder+' <span class="visible-xs"><br><br></span></div><div class="col-lg-11"><div class="alert alert-info"><i class="icon icon-info-sign"></i> <strong>Cours : </strong><br><br>'+addBriquePlaceholder+'</div></div>';
	}
}
 
function addSuperBrique(thisSelector, type) {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var id_exercice = $('#id-exercice').attr('data-exercice-id');
	
	var existingSuperBrique= new SuperBrique( thisSelector.closest('div.superbrique') );	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_editor_add_superbrique", {'id_exercice' : id_exercice, 'numero' : parseInt(existingSuperBrique.numero) + 1, 'type' : type }),
		success: function(data){	
			if (type == 'question') {
				var newSuperBrique     = new NewSuperBrique( data.id_question, parseInt(existingSuperBrique.numero) + 1, type, data.id_indice, data.id_correction );
			} else {
				var newSuperBrique     = new NewSuperBrique( data.id_question, parseInt(existingSuperBrique.numero) + 1, type);	
			}
			existingSuperBrique.increment(1);
			existingSuperBrique.selector.after(newSuperBrique.content);	
			$.unblockUI();				
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
	
}

function removeSuperBrique(thisSelector) {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingSuperBrique= new SuperBrique( thisSelector );
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_editor_remove_superbrique", {'id_superbrique' : parseInt(existingSuperBrique.id) }),
		success: function(){
			$.unblockUI();
			existingSuperBrique.increment(-1);
			existingSuperBrique.selector.fadeOut(400, function() { $(this).remove(); });
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
}

////////////////
// COMPLEMENT //
////////////////

function Complement(selector) {
	this.selector = selector,
	this.id = parseInt(selector.attr('data-complement-id')),
	this.numero = parseInt(selector.attr('data-complement-numero')),
	this.type = selector.attr('data-complement-type')
}

function NewComplement(superbrique, id, type, numero) {
	this.superbrique = superbrique,
	this.id = id,
	this.type = type,
	this.numero = numero,
	this.loop = parseInt(this.numero) - 1,
	this.content
}


////////////
// BRIQUE //
////////////

function Brique(selector) {	
	this.selector = selector,
	this.numero = parseInt(selector.attr('data-brique-numero')),
	this.id = parseInt(selector.attr('data-brique-id')),
	this.type = selector.attr('data-brique-type'),
	this.couche = parseInt(selector.attr('data-brique-couche')),
	
	this.increment = function(incr) {
		var numero = this.numero;
		this.selector.siblings('div.brique[data-brique-numero]').each( function() {
			if ( $(this).attr('data-brique-numero') > numero )
			{
				$(this).attr('data-brique-numero', parseInt($(this).attr('data-brique-numero')) + incr);
			}
		});
	}
	
	this.plusPetitNumeroReponse = function() {
		return this.getPrecedingInputsNumberNotIncluding() + 1;
	};
	
	this.nombreReponses = function() {
		return this.getPrecedingInputsNumberIncluding() - this.getPrecedingInputsNumberNotIncluding();
	};
	
	this.removeReponses = function() {	
		var ppn = this.plusPetitNumeroReponse();
		var decr = this.selector.closest('div.superbrique').find('tr[data-brique-id="'+this.id+'"]').length;
		this.selector.closest('div.superbrique').find('tr[data-brique-id="'+this.id+'"]').each( function() {
			var existingReponse = new Reponse($(this));
			existingReponse.reorganizeCheckReponseBeforeDeletion();
			$(this).fadeOut(400, function() { $(this).remove(); });
			existingReponse.reorganizeCheckReponseAfterDeletion();
		});
		this.selector.closest('div.superbrique').find('tr[data-reponse-numero]').each( function() {
			if ( $(this).attr('data-reponse-numero') > ppn ) {
				var newNumeroReponse = parseInt($(this).attr('data-reponse-numero')) - decr;
				$(this).attr('data-reponse-numero', newNumeroReponse);
				$(this).find('td:first-child strong').html(newNumeroReponse);
			}
		});
	};
	
	this.getPrecedingInputsNumberNotIncluding = function() { 
		return this.selector.prevAll('.has-inputs').find('.is-input').length;
	}
	
	this.getPrecedingInputsNumberIncluding = function() {
		return this.selector.find('.is-input').length + this.selector.prevAll('.has-inputs').find('.is-input').length;
		// Utiliser Math.max et .attr('data-reponse-numero') ?
	}
}

function NewBrique(id, numero, type, clavier, couche) {	
	this.id = id,
	this.numero = numero,
	this.type = type;

	if (this.type == 'textnmaths') {
		this.content = '<div class="brique position-relative display-inline-block vertical-align-middle" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique">';
		this.content = this.content + '<span class="mathquill-textbox is-editable mathquill-update"></span>';
		this.content = this.content + '</div> </div>';
	}	
	else if (this.type == 'retour_ligne') {
		this.content = '<div class="brique position-relative" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique">';
		this.content = this.content + '<em class="structure" style="opacity:0.5;color:#c8c8c8;">retour à la ligne</em><br>';
		this.content = this.content + '</div> </div>';
	}
	else if (this.type == 'saut_ligne') {
		this.content = '<div class="brique position-relative" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique">';
		this.content = this.content + '<em class="structure" style="opacity:0.5;color:#c8c8c8;">saut de ligne</em><br><br>';
		this.content = this.content + '</div> </div>';
	}
	else if (this.type == 'case') {
		this.content = '<div class="brique has-inputs position-relative display-inline-block vertical-align-middle" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse">';
		this.content = this.content + '<div class="case is-input"></div>';
		this.content = this.content + '</div> </div>';
	}
	else if (this.type == 'case maths') {
		this.content = '<div class="brique has-inputs position-relative display-inline-block vertical-align-middle" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse">';
		this.content = this.content + '<span class="mathquill-editable is-editable mathquill-update"></span>';
		this.content = this.content + '</div> </div>';
	}
	else if (this.type == 'radio') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;4&quot;:{&quot;contenu&quot;:&quot;&quot;}}';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-add-choice"><span class="is-input"></span>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <input type="radio" name="radio-'+this.id+'" /> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <input type="radio" name="radio-'+this.id+'" /> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <input type="radio" name="radio-'+this.id+'" /> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <input type="radio" name="radio-'+this.id+'" /> <span data-key="4" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'checkbox') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;4&quot;:{&quot;contenu&quot;:&quot;&quot;}}';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-add-choice">';
		this.content = this.content + '<div class="position-relative"><div class="checkbox is-input remove-choice"> <input type="checkbox" /> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="checkbox is-input remove-choice"> <input type="checkbox" /> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="checkbox is-input remove-choice"> <input type="checkbox" /> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="checkbox is-input remove-choice"> <input type="checkbox" /> <span data-key="4" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'equations') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;} }';
		this.content = '<br> <div class="brique position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-add-choice">';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="1" class="mathquill-editable is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="2" class="mathquill-editable is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="3" class="mathquill-editable is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'systeme') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;} }';
		this.content = '<br> <div class="brique position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-add-choice">';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="1" class="mathquill-editable is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="2" class="mathquill-editable is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'liste') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;} }';
		this.content = '<br> <div class="brique position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-add-choice"><ul>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '</ul> </div> </div> <br>';
	}
	else if (this.type == 'liste ordonnee') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;} }';
		this.content = '<br> <div class="brique position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-add-choice"><ol>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '<li class="position-relative"><div class="radio remove-choice"> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div></li>';
		this.content = this.content + '</ol> </div> </div> <br>';
	}
	else if (this.type == 'liste deroulante') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;4&quot;:{&quot;contenu&quot;:&quot;&quot;}}';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-add-choice"><span class="is-input"></span>';
		this.content = this.content + '<small class="text-red">Liste déroulante : attention, ne pas inclure de mathématiques entre $</small><br>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '<div class="position-relative"><div class="radio remove-choice"> <span data-key="4" class="mathquill-textbox is-multiple mathquill-update"></span> </div></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'vignettes') {
		var contenu = '{&quot;1&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;contenu&quot;:&quot;&quot;}, &quot;4&quot;:{&quot;contenu&quot;:&quot;&quot;}}';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle width-100" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-add-choice"><br>';
		this.content = this.content + '<div class="position-relative"> <div class="well is-input remove-choice"> <span data-key="1" class="mathquill-textbox is-multiple mathquill-update"></span> </div> </div>';
		this.content = this.content + '<div class="position-relative"> <div class="well is-input remove-choice"> <span data-key="2" class="mathquill-textbox is-multiple mathquill-update"></span> </div> </div>';
		this.content = this.content + '<div class="position-relative"> <div class="well is-input remove-choice"> <span data-key="3" class="mathquill-textbox is-multiple mathquill-update"></span> </div> </div>';
		this.content = this.content + '<div class="position-relative"> <div class="well is-input remove-choice"> <span data-key="4" class="mathquill-textbox is-multiple mathquill-update"></span> </div> </div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'tableau') {
		var contenu = '{&quot;1&quot;:{&quot;1&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;} }, &quot;2&quot;:{&quot;1&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;}, &quot;2&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;}, &quot;3&quot;:{&quot;input&quot:0, &quot;contenu&quot;:&quot;&quot;} } }';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle width-100" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-edit-tableau"><br>';		
		this.content = this.content + '<div class="table-responsive"><table class="table table-bordered"><tbody>';
		this.content = this.content + '<tr data-cell-row="1"><td data-cell-row="1" data-cell-col="1"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td><td data-cell-row="1" data-cell-col="2"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td><td data-cell-row="1" data-cell-col="3"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td></tr>';
		this.content = this.content + '<tr data-cell-row="2"><td data-cell-row="2" data-cell-col="1"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td><td data-cell-row="2" data-cell-col="2"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td><td data-cell-row="2" data-cell-col="3"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td></tr>';
		this.content = this.content + '</tbody></table></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'tableau analyse') {
		var contenu = '{&quot;1&quot;:{&quot;type&quot;:&quot;entete&quot;,&quot;contenu&quot;:{&quot;1&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;},&quot;2&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;},&quot;3&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;},&quot;4&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;},&quot;5&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;},&quot;6&quot;:{&quot;input&quot;:0,&quot;contenu&quot;:&quot;&quot;}}}}';
		this.content = '<br> <div class="brique has-inputs position-relative display-inline-block vertical-align-middle width-100" data-brique-contenu="'+contenu+'" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique-reponse-edit-tableau-analyse"><br>';		
		this.content = this.content + '<div class="table-responsive"><table class="table table-borderless"><tbody>';
		this.content = this.content + '<tr data-cell-row="1" class="tableau-analyse"><td data-cell-col="1" class="col-lg-2 position-relative" style="border-right:1px solid black;"><div class="text-center"><span class="mathquill-editable is-cell-analyse mathquill-update"></span></div></td><td data-cell-col="2"><div class="text-center"><span class="mathquill-editable is-cell-analyse mathquill-update"></span></div></td><td data-cell-col="3"><div class="text-center"></div></td><td data-cell-col="4"><div class="text-center"><span class="mathquill-editable is-cell-analyse mathquill-update"></span></div></td><td data-cell-col="5"><div class="text-center"></div></td><td data-cell-col="6"><div class="text-center"><span class="mathquill-editable is-cell-analyse mathquill-update"></span></div></td></tr>';
		this.content = this.content + '</tbody></table></div>';
		this.content = this.content + '</div> </div> <br>';
	}
	else if (this.type == 'figure graphe') {
		this.content = '<div class="brique position-relative" data-brique-id="'+this.id+'" data-brique-numero="'+this.numero+'" data-brique-type="'+this.type+'" data-brique-couche="0"> ';
		this.content = this.content + '<div class="add-remove-brique">';
		this.content = this.content + '<article class="geogebraweb" data-param-width="500" data-param-height="400" data-param-showResetIcon="false" data-param-enableRightClick="true" data-param-enableLabelDrags="true" data-param-showMenuBar="true" data-param-showToolBar="true" data-param-showAlgebraInput="true" data-param-useBrowserForJS="false" data-param-ggbbase64="UEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAWAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc0srzUsuyczPU0hPT/LP88zLLNHQVKiuBQBQSwcI1je9uRkAAAAXAAAAUEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAMAAAAZ2VvZ2VicmEueG1svVbbbtw2EH12vmKgZ3tXFHVbQ+sgCRAggBsEdVoUfaMkWstaKwoi9+LCP9Tv6I91hpR213ZaNHZR2NrRkHM7w5kRi7f7dQtbORilu2XAZmEAsqt0rbpmGWzs7UUevL16UzRSN7IcBNzqYS3sMohnUXDUQ26WhqSs6mUgKrmQvOIXWSzqi5hX1cUiY9VFyeskXUR5lnMZAOyNuuz0Z7GWpkeVm2ol1+JaV8I6mytr+8v5fLfbzSbvMz0086YpZ3tTB4CRd2YZjC+XaO6R0o478SgM2fyXH669+QvVGSu6Cv0Tqo26enNW7FRX6x3sVG1XyyAPEcZKqmaFMFNi5iTUI9ZeVlZtpUHVE9Zhtus+cGKio/0z/wbtAU4AtdqqWg7LIJxFSQB6ULKz4y4bvcwn/WKr5M4bojfnIw4XGSZdGVW2chncitYgDtXdDphDDGHYIGvsfStLMUz8MQJ2jn8ooH6XZAuBeeC4E4bn9GT4JMmI+NRxAFbr1lkNIVnAwwNEYRTCORHmSYQkTf1W6NdC7knkSexJ4mVirx570djLxF4m5v+Ac+SPQMeFR0gnnPxbOPFUz+PwOc78BCcjEA/AKHpHOFDczMVPJB7Z1LOZIyz0hI2bOf24fKWvRMRfhIidePX18D1OJ5dJmv97l9FrXB5QRt9CGSV/g/KVyZ2csuTEKfpy/+555pJH3+PyWSu+wGMav6b3X+AwC/8Ph8V8mnTF2HtgViQ71o6Va0NThy/c4AEGCTZmmuGcSIAtkGTUoBGwBOIEWZZDSjQDTj0ZA4ccSI5xcOMlyfEndv2aQoK2aDHzjQs8hoQDc0MpBhxF4AYbDrmIo0SSQIJK5J2RW55CnCLDc4gxQBppGY0NjnrIo/MIOANOuiyDKIU0gozGIotpWqY5xY5GI0hDSEkV5yLORD8PUSMHTmiwwntt1CG5K9n2h1NxeVRdv7GPclet6+nV6ifSta7u3j/JtRTGTu8ohB+j40fOf5wefQPPilaUssWbwg2VAcBWtNTBzv6t7ixMJRD5tWYQ/UpV5kZai1oGfhNbcS2s3H9EaTMF6Fy7T3MhN1WraiW6n7FGyAQZhOlL7cbS9KWO49FLpfVQ39wbLBzY/yoHjcOEJXQ3ufcc95ypBBV24q4t96ecMyO3hzDFXpopL81ArTFmkphP5r1uj0u9Vp39IHq7GdwVCgfaQAG+65pWujy548PLSHVX6v2NTxD3tr7e98iNAZTNB93qAbC5ogTvC81IS0+dDEV2kAqdTOgkwinjqj7ss0XkJBwtPXVSeIQ+tBEpm2CycHKjjBsJlLaTgnHnT1ebTafs9cRYVd0dkZL85826xNIZ1R6bZP+RyWL+pFiKOzl0svUl0eFJbvTG+Bo91NlZsTHyi7Crd139o2ywub4Imm8WTXvRY8S1rNQaFf36mDpBx/oThupXa9kMckLYujurT6zbDU8L9NmyM/Vx0OtP3fYr1syTUIv5hKcw1aB6Kk0oceDeyWP11coIHNf1qR6CN4iiotGBibSUxADExq704K6l2ICIh8PHP//Y4p10wKHH4oA8nqq6phzv4Vd/AVBLBwjFpeoqnAQAADcMAABQSwECFAAUAAgACABIkldE1je9uRkAAAAXAAAAFgAAAAAAAAAAAAAAAAAAAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc1BLAQIUABQACAAIAEiSV0TFpeoqnAQAADcMAAAMAAAAAAAAAAAAAAAAAF0AAABnZW9nZWJyYS54bWxQSwUGAAAAAAIAAgB+AAAAMwUAAAAA"></article>';
		this.content = this.content + '</div> </div>';
	}
}

function addBrique(thisSelector, type, clavier) {
	clavier = typeof clavier !== 'undefined' ? clavier : '';
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	if (thisSelector.closest('div.complement').length > 0) {
		var existingComplement = new Complement( thisSelector.closest('div.complement') );
		var existingBrique       = new Brique( thisSelector.closest('div.brique') );
		if (type == 'liste' || type == 'liste ordonnee' || type == 'equations') {
			var contenu = { 1:{'contenu':''}, 2:{'contenu':''}, 3:{'contenu':''}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'systeme') {
			var contenu = { 1:{'contenu':''}, 2:{'contenu':''}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'tableau') {
			var contenu = { 1:{ 1:{'input':0, 'contenu':''}, 2:{'input':0, 'contenu':''}, 3:{'input':0, 'contenu':''}}, 2:{ 1:{'input':0, 'contenu':''}, 2:{'input':0, 'contenu':''}, 3:{'input':0, 'contenu':''}} };
			contenu = JSON.stringify(contenu);
		} else if (type == 'tableau analyse') {
			var contenu = {1:{'type':'entete','contenu':{1:{'input':0,'contenu':''},2:{'input':0,'contenu':''},3:{'input':0,'contenu':''},4:{'input':0,'contenu':''},5:{'input':0,'contenu':''},6:{'input':0,'contenu':''}}}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'figure graphe') {
			var contenu = "UEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAWAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc0srzUsuyczPU0hPT/LP88zLLNHQVKiuBQBQSwcI1je9uRkAAAAXAAAAUEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAMAAAAZ2VvZ2VicmEueG1svVbbbtw2EH12vmKgZ3tXFHVbQ+sgCRAggBsEdVoUfaMkWstaKwoi9+LCP9Tv6I91hpR213ZaNHZR2NrRkHM7w5kRi7f7dQtbORilu2XAZmEAsqt0rbpmGWzs7UUevL16UzRSN7IcBNzqYS3sMohnUXDUQ26WhqSs6mUgKrmQvOIXWSzqi5hX1cUiY9VFyeskXUR5lnMZAOyNuuz0Z7GWpkeVm2ol1+JaV8I6mytr+8v5fLfbzSbvMz0086YpZ3tTB4CRd2YZjC+XaO6R0o478SgM2fyXH669+QvVGSu6Cv0Tqo26enNW7FRX6x3sVG1XyyAPEcZKqmaFMFNi5iTUI9ZeVlZtpUHVE9Zhtus+cGKio/0z/wbtAU4AtdqqWg7LIJxFSQB6ULKz4y4bvcwn/WKr5M4bojfnIw4XGSZdGVW2chncitYgDtXdDphDDGHYIGvsfStLMUz8MQJ2jn8ooH6XZAuBeeC4E4bn9GT4JMmI+NRxAFbr1lkNIVnAwwNEYRTCORHmSYQkTf1W6NdC7knkSexJ4mVirx570djLxF4m5v+Ac+SPQMeFR0gnnPxbOPFUz+PwOc78BCcjEA/AKHpHOFDczMVPJB7Z1LOZIyz0hI2bOf24fKWvRMRfhIidePX18D1OJ5dJmv97l9FrXB5QRt9CGSV/g/KVyZ2csuTEKfpy/+555pJH3+PyWSu+wGMav6b3X+AwC/8Ph8V8mnTF2HtgViQ71o6Va0NThy/c4AEGCTZmmuGcSIAtkGTUoBGwBOIEWZZDSjQDTj0ZA4ccSI5xcOMlyfEndv2aQoK2aDHzjQs8hoQDc0MpBhxF4AYbDrmIo0SSQIJK5J2RW55CnCLDc4gxQBppGY0NjnrIo/MIOANOuiyDKIU0gozGIotpWqY5xY5GI0hDSEkV5yLORD8PUSMHTmiwwntt1CG5K9n2h1NxeVRdv7GPclet6+nV6ifSta7u3j/JtRTGTu8ohB+j40fOf5wefQPPilaUssWbwg2VAcBWtNTBzv6t7ixMJRD5tWYQ/UpV5kZai1oGfhNbcS2s3H9EaTMF6Fy7T3MhN1WraiW6n7FGyAQZhOlL7cbS9KWO49FLpfVQ39wbLBzY/yoHjcOEJXQ3ufcc95ypBBV24q4t96ecMyO3hzDFXpopL81ArTFmkphP5r1uj0u9Vp39IHq7GdwVCgfaQAG+65pWujy548PLSHVX6v2NTxD3tr7e98iNAZTNB93qAbC5ogTvC81IS0+dDEV2kAqdTOgkwinjqj7ss0XkJBwtPXVSeIQ+tBEpm2CycHKjjBsJlLaTgnHnT1ebTafs9cRYVd0dkZL85826xNIZ1R6bZP+RyWL+pFiKOzl0svUl0eFJbvTG+Bo91NlZsTHyi7Crd139o2ywub4Imm8WTXvRY8S1rNQaFf36mDpBx/oThupXa9kMckLYujurT6zbDU8L9NmyM/Vx0OtP3fYr1syTUIv5hKcw1aB6Kk0oceDeyWP11coIHNf1qR6CN4iiotGBibSUxADExq704K6l2ICIh8PHP//Y4p10wKHH4oA8nqq6phzv4Vd/AVBLBwjFpeoqnAQAADcMAABQSwECFAAUAAgACABIkldE1je9uRkAAAAXAAAAFgAAAAAAAAAAAAAAAAAAAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc1BLAQIUABQACAAIAEiSV0TFpeoqnAQAADcMAAAMAAAAAAAAAAAAAAAAAF0AAABnZW9nZWJyYS54bWxQSwUGAAAAAAIAAgB+AAAAMwUAAAAA";
		}
		else {
			var contenu;
		}
		$.ajax({
			type: "POST",
			data: { 'contenu' : contenu },
			url: Routing.generate("majordesk_app_editor_add_brique_to_complement", {'id_complement' : existingComplement.id, 'numero' : parseInt(existingBrique.numero) + 1, 'type' : type, 'couche' : existingBrique.couche }),
			success: function(data){	
				var newBrique            = new NewBrique( data.id_brique, parseInt(existingBrique.numero) + 1, type, clavier, existingBrique.couche );		
				existingBrique.increment(1);
				existingBrique.selector.after(newBrique.content);
				if (type == 'textnmaths' || type == 'liste' || type == 'liste ordonnee' || type == 'tableau') {
					$('.mathquill-update').mathquill('textbox').first().find('textarea').focus();
					$('.mathquill-update').removeClass('mathquill-update');
				} else if (type == 'equations' || type == 'systeme' || type == 'tableau analyse') {
					$('.mathquill-update').mathquill('editable').first().find('textarea').focus();
					$('.mathquill-update').removeClass('mathquill-update');
				}
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti');
			}
		});	
	}
	else {
		var existingSuperbrique  = new SuperBrique( thisSelector.closest('div.superbrique') );
		var existingBrique       = new Brique( thisSelector.closest('div.brique') );
		if (type == 'liste' || type == 'liste ordonnee' || type == 'equations') {
			var contenu = { 1:{'contenu':''}, 2:{'contenu':''}, 3:{'contenu':''}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'systeme') {
			var contenu = { 1:{'contenu':''}, 2:{'contenu':''}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'tableau') {
			var contenu = { 1:{ 1:{'input':0, 'contenu':''}, 2:{'input':0, 'contenu':''}, 3:{'input':0, 'contenu':''}}, 2:{ 1:{'input':0, 'contenu':''}, 2:{'input':0, 'contenu':''}, 3:{'input':0, 'contenu':''}} };
			contenu = JSON.stringify(contenu);
		} else if (type == 'tableau analyse') {
			var contenu = {1:{'type':'entete','contenu':{1:{'input':0,'contenu':''},2:{'input':0,'contenu':''},3:{'input':0,'contenu':''},4:{'input':0,'contenu':''},5:{'input':0,'contenu':''},6:{'input':0,'contenu':''}}}};
			contenu = JSON.stringify(contenu);
		} else if (type == 'figure graphe') {
			var contenu = "UEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAWAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc0srzUsuyczPU0hPT/LP88zLLNHQVKiuBQBQSwcI1je9uRkAAAAXAAAAUEsDBBQACAAIAEiSV0QAAAAAAAAAAAAAAAAMAAAAZ2VvZ2VicmEueG1svVbbbtw2EH12vmKgZ3tXFHVbQ+sgCRAggBsEdVoUfaMkWstaKwoi9+LCP9Tv6I91hpR213ZaNHZR2NrRkHM7w5kRi7f7dQtbORilu2XAZmEAsqt0rbpmGWzs7UUevL16UzRSN7IcBNzqYS3sMohnUXDUQ26WhqSs6mUgKrmQvOIXWSzqi5hX1cUiY9VFyeskXUR5lnMZAOyNuuz0Z7GWpkeVm2ol1+JaV8I6mytr+8v5fLfbzSbvMz0086YpZ3tTB4CRd2YZjC+XaO6R0o478SgM2fyXH669+QvVGSu6Cv0Tqo26enNW7FRX6x3sVG1XyyAPEcZKqmaFMFNi5iTUI9ZeVlZtpUHVE9Zhtus+cGKio/0z/wbtAU4AtdqqWg7LIJxFSQB6ULKz4y4bvcwn/WKr5M4bojfnIw4XGSZdGVW2chncitYgDtXdDphDDGHYIGvsfStLMUz8MQJ2jn8ooH6XZAuBeeC4E4bn9GT4JMmI+NRxAFbr1lkNIVnAwwNEYRTCORHmSYQkTf1W6NdC7knkSexJ4mVirx570djLxF4m5v+Ac+SPQMeFR0gnnPxbOPFUz+PwOc78BCcjEA/AKHpHOFDczMVPJB7Z1LOZIyz0hI2bOf24fKWvRMRfhIidePX18D1OJ5dJmv97l9FrXB5QRt9CGSV/g/KVyZ2csuTEKfpy/+555pJH3+PyWSu+wGMav6b3X+AwC/8Ph8V8mnTF2HtgViQ71o6Va0NThy/c4AEGCTZmmuGcSIAtkGTUoBGwBOIEWZZDSjQDTj0ZA4ccSI5xcOMlyfEndv2aQoK2aDHzjQs8hoQDc0MpBhxF4AYbDrmIo0SSQIJK5J2RW55CnCLDc4gxQBppGY0NjnrIo/MIOANOuiyDKIU0gozGIotpWqY5xY5GI0hDSEkV5yLORD8PUSMHTmiwwntt1CG5K9n2h1NxeVRdv7GPclet6+nV6ifSta7u3j/JtRTGTu8ohB+j40fOf5wefQPPilaUssWbwg2VAcBWtNTBzv6t7ixMJRD5tWYQ/UpV5kZai1oGfhNbcS2s3H9EaTMF6Fy7T3MhN1WraiW6n7FGyAQZhOlL7cbS9KWO49FLpfVQ39wbLBzY/yoHjcOEJXQ3ufcc95ypBBV24q4t96ecMyO3hzDFXpopL81ArTFmkphP5r1uj0u9Vp39IHq7GdwVCgfaQAG+65pWujy548PLSHVX6v2NTxD3tr7e98iNAZTNB93qAbC5ogTvC81IS0+dDEV2kAqdTOgkwinjqj7ss0XkJBwtPXVSeIQ+tBEpm2CycHKjjBsJlLaTgnHnT1ebTafs9cRYVd0dkZL85826xNIZ1R6bZP+RyWL+pFiKOzl0svUl0eFJbvTG+Bo91NlZsTHyi7Crd139o2ywub4Imm8WTXvRY8S1rNQaFf36mDpBx/oThupXa9kMckLYujurT6zbDU8L9NmyM/Vx0OtP3fYr1syTUIv5hKcw1aB6Kk0oceDeyWP11coIHNf1qR6CN4iiotGBibSUxADExq704K6l2ICIh8PHP//Y4p10wKHH4oA8nqq6phzv4Vd/AVBLBwjFpeoqnAQAADcMAABQSwECFAAUAAgACABIkldE1je9uRkAAAAXAAAAFgAAAAAAAAAAAAAAAAAAAAAAZ2VvZ2VicmFfamF2YXNjcmlwdC5qc1BLAQIUABQACAAIAEiSV0TFpeoqnAQAADcMAAAMAAAAAAAAAAAAAAAAAF0AAABnZW9nZWJyYS54bWxQSwUGAAAAAAIAAgB+AAAAMwUAAAAA";
		}
		else {
			var contenu;
		}
		$.ajax({
			type: "POST",
			data: { 'contenu' : contenu },
			url: Routing.generate("majordesk_app_editor_add_brique_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'numero' : parseInt(existingBrique.numero) + 1, 'type' : type, 'couche' : existingBrique.couche }),
			success: function(data){	
				var newBrique            = new NewBrique( data.id_brique, parseInt(existingBrique.numero) + 1, type, clavier, existingBrique.couche );		
				existingBrique.increment(1);
				existingBrique.selector.after(newBrique.content);
				if (type == 'textnmaths' || type == 'liste' || type == 'liste ordonnee' || type == 'tableau') {
					$('.mathquill-update').mathquill('textbox').first().find('textarea').focus();
					$('.mathquill-update').removeClass('mathquill-update');
				} else if (type == 'equations' || type == 'systeme' || type == 'tableau analyse') {
					$('.mathquill-update').mathquill('editable').first().find('textarea').focus();
					$('.mathquill-update').removeClass('mathquill-update');
				}
				$.unblockUI();
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti');
			}
		});	
	}
}

function removeBrique(thisSelector, fond) {
	if (fond == 'complement') {
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( thisSelector );
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_editor_remove_brique_from_complement", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
				existingBrique.increment(-1);
				existingBrique.selector.fadeOut(400, function() { $(this).remove(); });
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti');
			}
		});
	}
	else if (fond == 'superbrique') {
		$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			'border-radius': '10px', 
			opacity: .5, 
			color: '#fff' 
		} });
		var existingBrique= new Brique( thisSelector );
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_editor_remove_brique_from_superbrique", {'id_brique' : parseInt(existingBrique.id) }),
			success: function(){
				$.unblockUI();
				existingBrique.increment(-1);
				// if ( existingBrique.selector.hasClass('jsgbox') || existingBrique.selector.hasClass('jsggraph')) {
					// delete boards[thisSelector.siblings('div[id^="box_"]').first().attr('id')];
				// }
				existingBrique.selector.fadeOut(400, function() { $(this).remove(); });
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti');
			}
		});
	}
}

function addBriqueAndReponse(thisSelector, type, clavier) {
	clavier = typeof clavier !== 'undefined' ? clavier : '0';
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	if (thisSelector.closest('div.complement').length > 0) {
		$.unblockUI();
		alert("Opération impossible : une réponse élève doit figurer dans le corps de l'exercice");
	}
	else {
		var existingSuperbrique  = new SuperBrique( thisSelector.closest('div.superbrique') );
		var existingBrique       = new Brique( thisSelector );
		if (type == 'case' || type == 'case puissance' || type == 'case indice') {
			var contenu;
		} 
		else if (type == 'radio' || type == 'checkbox' || type == 'liste deroulante' || type == 'vignettes') {
			var contenu = { 1:{'contenu':''}, 2:{'contenu':''}, 3:{'contenu':''}, 4:{'contenu':''}};
		}
		
		$.ajax({
			type: "POST",
			data: { 'contenu' : JSON.stringify(contenu) },
			url: Routing.generate("majordesk_app_editor_add_brique_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'numero' : parseInt(existingBrique.numero) + 1, 'type' : type, 'couche' : existingBrique.couche }),
			success: function(data){	
				var newBrique            = new NewBrique( data.id_brique, parseInt(existingBrique.numero) + 1, type, clavier, existingBrique.couche );		
				existingBrique.increment(1);
				existingBrique.selector.after(newBrique.content);
				
				var precedingInputs = existingBrique.getPrecedingInputsNumberIncluding();
				if (type == 'case' || type == 'case puissance' || type == 'case indice') {
					var constructor = { 1:{'contenu':'', 'type':'expression exacte'} };
				} 
				else if (type == 'radio') {
					var constructor = { 1:{'contenu':'', 'type':'radio'} };
				}
				else if (type == 'checkbox') {
					var constructor = { 1:{'contenu':'0', 'type':'checkbox'}, 2:{'contenu':'0', 'type':'checkbox'}, 3:{'contenu':'0', 'type':'checkbox'}, 4:{'contenu':'0', 'type':'checkbox'} };
				}
				else if (type == 'liste deroulante') {
					var constructor = { 1:{'contenu':'', 'type':'liste deroulante'} };
				}
				else if (type == 'vignettes') {
					var constructor = { 1:{'contenu':'1', 'type':'vignette'}, 2:{'contenu':'2', 'type':'vignette'}, 3:{'contenu':'3', 'type':'vignette'}, 4:{'contenu':'4', 'type':'vignette'} };
				}
				
				$.ajax({
					type: "POST",
					data: { 'constructor' : JSON.stringify(constructor) },
					url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : data.id_brique, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : clavier }),
					success: function(data2) {	
						$.unblockUI();
						var newReponse;
						var indx = 1;
						jQuery.each(data2.ids, function(i, val) {
							newReponse = new NewReponse(existingSuperbrique.id, data.id_brique, val, precedingInputs + 1, clavier, constructor[indx]['contenu'], constructor[indx]['type'], existingSuperbrique.getCurrentDependances());
							existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
							if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
								existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
							} else {
								existingSuperbrique.getTbodySelector().prepend(newReponse.content);
							}
							precedingInputs++;
							indx++;
						});
						if (type == 'case' || type == 'case puissance' || type == 'case indice') {
							$('.mathquill-update').mathquill('textbox');
							$('.mathquill-update').removeClass('mathquill-update');
							$('.mathquill-update-reponse').mathquill('editable').find('textarea').focus();
							$('.mathquill-update-reponse').removeClass('mathquill-update-reponse');
						} else {
							$('.mathquill-update').mathquill('textbox').find('textarea').first().focus();
							$('.mathquill-update').removeClass('mathquill-update');
							$('.mathquill-update-reponse').mathquill('editable');
							$('.mathquill-update-reponse').removeClass('mathquill-update-reponse');
						}
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti');
					}
				});
			},
			error: function() {
				$.unblockUI();
				alert('La requête n\'a pas abouti');
			}
		});	
	}
}

function removeBriqueAndReponses(thisSelector) {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( thisSelector );
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_editor_remove_brique_and_reponses", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			$.unblockUI();
			existingBrique.removeReponses();
			existingBrique.increment(-1);
			existingBrique.selector.fadeOut(400, function() { $(this).remove(); });
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
}

// =========================== //

$(document)
// Cocher/Décocher une dépendance
.on('click', ".dependance-checkbox", function() {
	tickReponse($(this));
})

// Changer le type d'un mapping
.on('click', ".select-mapping-type", function() {
	updateMappingType($(this));
})
.on('click', ".reset-mapping-type", function() {
	var thisSelector = $(this);
	var checkNumero = thisSelector.attr('data-radio-numero');
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_editor_reset_mapping_type", {'id_reponse' : thisSelector.closest('tbody').find('input[data-check-numero="'+checkNumero+'"]:checked').first().closest('tr').attr('data-reponse-id')}),
		success: function() {
			thisSelector.closest('div.mapping-type-adjuster').find('input[data-radio-numero]').attr('checked', false);
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
});

function updateMappingType(thisSelector) {
	var checkNumero = thisSelector.attr('data-radio-numero');
	var type = thisSelector.val();
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	$.ajax({
		type: "POST",
		data: { 'type' : type },
		url: Routing.generate("majordesk_app_editor_update_mapping_type", {'id_reponse' : thisSelector.closest('tbody').find('input[data-check-numero="'+checkNumero+'"]:checked').first().closest('tr').attr('data-reponse-id')}),
		success: function() {	
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
}

function addReponse(thisSelector) {
	var existingReponse= new Reponse( thisSelector );
	var existingSuperbrique= new SuperBrique( thisSelector.closest('div.superbrique') );
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	$.ajax({
		type: "POST",
		// data: { 'constructor' : JSON.stringify(constructor) },
		url: Routing.generate("majordesk_app_editor_add_reponse_to_mapping", {'id_reponse' : existingReponse.id}),
		success: function(data) {	
			newReponse = new NewReponse(thisSelector.closest('div.superbrique'), thisSelector.attr('data-brique-id'), data.id_reponse, existingReponse.numero, existingReponse.clavier, '', existingReponse.type, existingSuperbrique.getCurrentDependances());
			thisSelector.after(newReponse.content);
			
			if (thisSelector.find('input[data-check-numero]:checked').length > 0) {
				var check_numero = thisSelector.find('input[data-check-numero]:checked').attr('data-check-numero');
				$('tr[data-reponse-id="'+data.id_reponse+'"]').find('input[data-check-numero="'+check_numero+'"]').attr("checked",true);
			} else {
				thisSelector.find('input[data-check-numero]:last').attr("checked",true);
				var thisCheckbox = $('tr[data-reponse-id="'+data.id_reponse+'"]').find('input[data-check-numero]:last');
				thisCheckbox.attr("checked",true);
				var checkReponse = new CheckReponse(existingReponse, thisCheckbox);
				checkReponse.addColumn();
			}

			$('.mathquill-update-reponse').mathquill('editable').find('textarea').focus();
			$('.mathquill-update-reponse').removeClass('mathquill-update-reponse');
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti');
		}
	});
}


function removeReponse(thisSelector) {
	var existingReponse= new Reponse( thisSelector );
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	$.ajax({
		type: "POST",
		dataType: "json",
		url: Routing.generate("majordesk_app_editor_remove_reponse", {'id_reponse' : existingReponse.id }),
		success: function(){
			existingReponse.incrementAndRemove();
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : erreur lors de l\'opération');
		}
	});
}

// ACTIONS SPECIFIQUES

$(document)
.on('click', '.select-cell-type-case', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var cellInsert = $(this).closest('div.edit-cell');
	var thisTd = $(this).closest('td');
	var thisTr = $(this).closest('tr');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	var rowArr = contenuArr[thisTd.attr('data-cell-row')];
	rowArr[thisTd.attr('data-cell-col')].input = 1;
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	var existingSuperbrique= new SuperBrique( $(this).closest('div.superbrique') );
	var existingBrique= new Brique( $(this).closest('div.brique') );
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			var precedingInputs = existingBrique.getPrecedingInputsNumberNotIncluding() + thisTd.prevAll().find('.is-input').length + thisTr.prevAll().find('.is-input').length;
			var precedingInputsBrique = parseInt(existingBrique.nombreReponses())+1;
			var constructor = { };
			constructor[precedingInputsBrique] = {'contenu':'', 'type':'expression exacte'};
			$.ajax({
				type: "POST",
				data: { 'constructor' : JSON.stringify(constructor) },
				url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : existingBrique.id, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : 5 }),
				success: function(data2) {
					cellInsert.html('<div class="case is-input"></div>');
					cellInsert.attr('data-cell-input', 1);
					var newReponse;
					jQuery.each(data2.ids, function(i, val) { // une seule boucle, mais ids est un array
						newReponse = new NewReponse(existingSuperbrique.id, existingBrique.id, val, precedingInputs + 1, precedingInputsBrique, constructor[precedingInputsBrique]['contenu'], constructor[precedingInputsBrique]['type'], existingSuperbrique.getCurrentDependances());
						existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
						if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
							existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
						} else {
							existingSuperbrique.getTbodySelector().prepend(newReponse.content);
						}
						precedingInputs++;
					});
					
					$('.mathquill-update-reponse').mathquill('editable').find('textarea').first().focus();
					$('.mathquill-update-reponse').removeClass('mathquill-update-reponse');
					$.unblockUI();
				},
				error: function() {
					$.unblockUI();
					alert('La requête n\'a pas abouti : un problème est survenu..');
				}
			});
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.select-cell-type-textnmaths', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var cellInsert = $(this).closest('div.edit-cell');
	var thisTd = $(this).closest('td');
	var thisTr = $(this).closest('tr');
	var contenuArr  =  JSON.parse($(this).closest('div.brique').attr('data-brique-contenu')); 
	var rowArr = contenuArr[thisTd.attr('data-cell-row')];
	rowArr[thisTd.attr('data-cell-col')].input = 0;
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	var existingBrique= new Brique( $(this).closest('div.brique') );
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			var num = existingBrique.getPrecedingInputsNumberNotIncluding() + thisTd.prevAll().find('.is-input').length + thisTr.prevAll().find('.is-input').length + 1;
			var reponseSelector = existingBrique.selector.closest('div.superbrique').find('div.reponse').find('tr[data-reponse-numero="'+num+'"]').first(); // attention à first : ici pas de problème, mais que se passe t-il s'il y a des réponses alternatives...
			removeReponse(reponseSelector); // Le problème de faire une fonction comme celle-ci est qu'il faudrait pouvoir adapater la fonction de rappel (callback) qui comprendrait ce qui suit..
			cellInsert.html('<span class="mathquill-textbox is-cell mathquill-update">'+rowArr[thisTd.attr('data-cell-col')].contenu+'</span>');
			$('.mathquill-update').mathquill('textbox').find('textarea').focus();
			$('.mathquill-update').removeClass('mathquill-update');
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.add-tableau-row', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( $(this).closest('div.brique') );
	var contenuArr  =  JSON.parse(existingBrique.selector.attr('data-brique-contenu')); 
	var i_max = Object.keys(contenuArr).length;
	var i_max_plus = i_max + 1;
	var j_max = Object.keys(contenuArr[1]).length;
	var newRow = { };
	for(var j=1;j<=j_max;j++) {
		newRow[j] = {'input':0, 'contenu':''};
	}
	contenuArr[i_max_plus] = newRow;
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			var rowHtml = '<tr data-cell-row="'+i_max_plus+'">';
			for(var j=1;j<=j_max;j++) {
				rowHtml = rowHtml + '<td data-cell-row="'+i_max_plus+'" data-cell-col="'+j+'"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td>';
			}
			existingBrique.selector.find('tbody').append(rowHtml);
			$('.mathquill-update').mathquill('textbox').first().find('textarea').focus();
			$('.mathquill-update').removeClass('mathquill-update');
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.add-tableau-col', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( $(this).closest('div.brique') );
	var contenuArr  =  JSON.parse(existingBrique.selector.attr('data-brique-contenu')); 
	var i_max = Object.keys(contenuArr).length;
	var j_max = Object.keys(contenuArr[1]).length;
	var j_max_plus = j_max + 1;
	for(var i=1;i<=i_max;i++) {
		contenuArr[i][j_max_plus] = {'input':0, 'contenu':''};
	}
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			existingBrique.selector.find('tbody').children('tr').each(function() {
				var i_row = $(this).attr('data-cell-row');
				$(this).append('<td data-cell-row="'+i_row+'" data-cell-col="'+j_max_plus+'"><div class="position-relative"><div class="edit-cell text-center" data-cell-input="0" style="min-height:24px"><span class="mathquill-textbox is-cell mathquill-update"></span></div></div></td>');
			});
			$('.mathquill-update').mathquill('textbox').first().find('textarea').focus();
			$('.mathquill-update').removeClass('mathquill-update');
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.remove-cell-row', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var thisTd = $(this).closest('td');
	var thisTr = $(this).closest('tr');
	var i_0 = parseInt(thisTd.attr('data-cell-row'));
	var existingBrique= new Brique( $(this).closest('div.brique') );
	var contenuArr  =  JSON.parse(existingBrique.selector.attr('data-brique-contenu'));
	var i_max = Object.keys(contenuArr).length;
	var j_max = Object.keys(contenuArr[1]).length;	
	delete contenuArr[i_0];
	for(var i=i_0+1;i<=i_max;i++) {
		contenuArr[i-1] = contenuArr[i];
		delete contenuArr[i];
	}
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			existingBrique.selector.find('tbody').find('tr').each(function() {
				if ( $(this).attr('data-cell-row') == i_0 ) {
					$(this).find('td').each( function() {
						if ($(this).find('div.edit-cell').attr('data-cell-input')==1) {
							var num = existingBrique.getPrecedingInputsNumberNotIncluding() + $(this).prevAll().find('.is-input').length + thisTr.prevAll().find('.is-input').length + 1;
							var reponseSelector = existingBrique.selector.closest('div.superbrique').find('div.reponse').find('tr[data-reponse-numero="'+num+'"]').first(); // attention à first : ici pas de problème, mais que se passe t-il s'il y a des réponses alternatives...
							removeReponse(reponseSelector);
						}
					});
					$(this).fadeOut(function(){ $(this).remove() });
				}
				if ( $(this).attr('data-cell-row') > i_0 ) {
					$(this).attr('data-cell-row', $(this).attr('data-cell-row')-1);
					$(this).find('td').each(function() {
						$(this).attr('data-cell-row', $(this).attr('data-cell-row')-1);
					});
				}
			});
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.remove-cell-col', function() {
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var thisTd = $(this).closest('td');
	var thisTr = $(this).closest('tr');
	var j_0 = parseInt(thisTd.attr('data-cell-col'));
	var existingBrique= new Brique( $(this).closest('div.brique') );
	var contenuArr  =  JSON.parse(existingBrique.selector.attr('data-brique-contenu'));
	var i_max = Object.keys(contenuArr).length;
	var j_max = Object.keys(contenuArr[1]).length;	
	for(var i in contenuArr) {
		delete contenuArr[i][j_0];
		for(var j=j_0+1;j<=j_max;j++) {
			contenuArr[i][j-1] = contenuArr[i][j];
				delete contenuArr[i][j];
		}
	}
	
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			existingBrique.selector.find('tbody').find('tr').find('td').each(function() {
				if ( $(this).attr('data-cell-col') == j_0 ) {
					if ($(this).find('div.edit-cell').attr('data-cell-input')==1) {
						var num = existingBrique.getPrecedingInputsNumberNotIncluding() + $(this).prevAll().find('.is-input').length + thisTr.prevAll().find('.is-input').length + 1;
						var reponseSelector = existingBrique.selector.closest('div.superbrique').find('div.reponse').find('tr[data-reponse-numero="'+num+'"]').first(); // attention à first : ici pas de problème, mais que se passe t-il s'il y a des réponses alternatives...
						removeReponse(reponseSelector);
					}
					$(this).fadeOut(function(){ $(this).remove() });
				}
				else if ( $(this).attr('data-cell-col') > j_0 ) {
					$(this).attr('data-cell-col', $(this).attr('data-cell-col')-1);
				}
			});
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : les changements ne sont pas sauvegardés..');
		}
	});
})
.on('click', '.add-choice', function() {
	var existingSuperbrique= new SuperBrique( $(this).closest('div.superbrique') ); 
	var contenuArr = JSON.parse($(this).closest('div.brique').attr('data-brique-contenu'));
	var newChoiceNumber = parseInt(Object.keys(contenuArr).length) + 1 ;
	var newChoice = { "contenu" : "" };
	contenuArr[newChoiceNumber] = newChoice;
	$(this).closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	var existingBrique= new Brique( $(this).closest('div.brique') ); 
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			if (existingBrique.type == 'radio') {
				var ids_reponse = {};
				var id_reponse = {};
				var k=1;
				$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
					id_reponse = {};
					id_reponse["incr"] = 1;
					id_reponse["id_reponse"] = $(this).attr('data-reponse-id');
					ids_reponse[k] = id_reponse;
					k++;
				});
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'ids_reponse':JSON.stringify(ids_reponse) },
					url: Routing.generate("majordesk_app_editor_update_reponse_clavier", {'id_reponse' : id_reponse }),
					success: function(data){
						existingBrique.selector.children('div.add-remove-brique-reponse-add-choice').append('<div class="position-relative"><div class="radio remove-choice"><input type="radio" name="radio-'+existingBrique.id+'"><span data-key="'+data.clavier+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></div>');
						$('.mathquill-update').mathquill('textbox').find('textarea').focus();
						$('.mathquill-update').removeClass('mathquill-update');
						$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
							$('div.reponse tr[data-reponse-id="'+$(this).attr('data-reponse-id')+'"]').find('select.reponse-contenu-select').append('<option value="'+data.claviers[$(this).attr('data-reponse-id')]+'">'+data.claviers[$(this).attr('data-reponse-id')]+'</option>');
						});
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : erreur lors de l\'opération');
					}
				});
			} else if (existingBrique.type == 'liste deroulante') {
				var ids_reponse = {};
				var id_reponse = {};
				var k=1;
				$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
					id_reponse = {};
					id_reponse["incr"] = 1;
					id_reponse["id_reponse"] = $(this).attr('data-reponse-id');
					ids_reponse[k] = id_reponse;
					k++;
				});
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'ids_reponse':JSON.stringify(ids_reponse) },
					url: Routing.generate("majordesk_app_editor_update_reponse_clavier", {'id_reponse' : id_reponse }),
					success: function(data){
						existingBrique.selector.children('div.add-remove-brique-reponse-add-choice').append('<div class="position-relative"><div class="radio remove-choice"><span data-key="'+data.clavier+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></div>');
						$('.mathquill-update').mathquill('textbox').find('textarea').focus();
						$('.mathquill-update').removeClass('mathquill-update');
						$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
							$('div.reponse tr[data-reponse-id="'+$(this).attr('data-reponse-id')+'"]').find('select.reponse-contenu-select').append('<option value="'+data.claviers[$(this).attr('data-reponse-id')]+'">'+data.claviers[$(this).attr('data-reponse-id')]+'</option>');
						});
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : erreur lors de l\'opération');
					}
				});
			} else if (existingBrique.type == 'checkbox') {
				var precedingInputs = existingBrique.getPrecedingInputsNumberIncluding();
				var precedingInputsPlus = parseInt(precedingInputs) + 1;
				var constructor = { };
				constructor[precedingInputsPlus] = {'contenu':'0', 'type':'checkbox'};
				$.ajax({
					type: "POST",
					data: { 'constructor' : JSON.stringify(constructor) },
					url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : existingBrique.id, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : 0 }),
					success: function(data2) {	
						var dataKey = parseInt(existingBrique.selector.find('.is-input').length) + 1;
						existingBrique.selector.children('div.add-remove-brique-reponse-add-choice').append('<div class="position-relative"><div class="checkbox is-input remove-choice"><input type="checkbox"><span data-key="'+dataKey+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></div>');
						$('.mathquill-update').mathquill('textbox').find('textarea').focus();
						$('.mathquill-update').removeClass('mathquill-update');
						$.unblockUI();
						var newReponse;
						jQuery.each(data2.ids, function(i, val) { // une seule boucle, mais ids est un array
							newReponse = new NewReponse(existingSuperbrique.id, existingBrique.id, val, precedingInputs + 1, 0, constructor[precedingInputsPlus]['contenu'], constructor[precedingInputsPlus]['type'], existingSuperbrique.getCurrentDependances());
							existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
							if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
								existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
							} else {
								existingSuperbrique.getTbodySelector().prepend(newReponse.content);
							}
							precedingInputs++;
						});
						
						$('.mathquill-update').mathquill('editable').find('textarea').first().focus();
						$('.mathquill-update').removeClass('mathquill-update');
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti');
					}
				});
			} else if (existingBrique.type == 'vignettes') {
				var precedingInputs = existingBrique.getPrecedingInputsNumberIncluding();
				var precedingInputsBrique = parseInt(existingBrique.nombreReponses())+1;
				var constructor = { };
				constructor[precedingInputsBrique] = {'contenu':precedingInputsBrique, 'type':'vignette'};
				$.ajax({
					type: "POST",
					data: { 'constructor' : JSON.stringify(constructor) },
					url: Routing.generate("majordesk_app_editor_add_reponse_to_superbrique", {'id_superbrique' : existingSuperbrique.id, 'id_brique' : existingBrique.id, 'numero' : parseInt(precedingInputs) + 1, 'clavier' : precedingInputsBrique }),
					success: function(data2) {
						var dataKey = parseInt(existingBrique.selector.find('.is-input').length) + 1; // ou .nombreReponses() ...
						var ids_reponse = {};
						var id_reponse = {};
						var k=1;
						$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
							id_reponse = {};
							id_reponse["incr"] = 1;
							id_reponse["id_reponse"] = $(this).attr('data-reponse-id');
							ids_reponse[k] = id_reponse;
							k++;
						});
						$.ajax({
							type: "POST",
							dataType: "json",
							data: { 'ids_reponse':JSON.stringify(ids_reponse) },
							url: Routing.generate("majordesk_app_editor_update_reponse_clavier"),
							success: function(data){
								$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
									$('div.reponse tr[data-reponse-id="'+$(this).attr('data-reponse-id')+'"]').find('select.reponse-contenu-select').append('<option value="'+data.claviers[$(this).attr('data-reponse-id')]+'">'+data.claviers[$(this).attr('data-reponse-id')]+'</option>');
								});
								existingBrique.selector.children('div.add-remove-brique-reponse-add-choice').append('<div class="position-relative"> <div class="well is-input remove-choice"> <span data-key="'+dataKey+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></div>');
								$('.mathquill-update').mathquill('textbox').find('textarea').focus();
								$('.mathquill-update').removeClass('mathquill-update');
								
								var newReponse;
								jQuery.each(data2.ids, function(i, val) { // une seule boucle, mais ids est un array
									newReponse = new NewReponse(existingSuperbrique.id, existingBrique.id, val, precedingInputs + 1, precedingInputsBrique, constructor[precedingInputsBrique]['contenu'], constructor[precedingInputsBrique]['type'], existingSuperbrique.getCurrentDependances());
									existingSuperbrique.incrementAllReponseAbove(precedingInputs, 1);
									if ( existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').length != 0 ) {		
										existingSuperbrique.getTbodySelector().children('tr[data-reponse-numero="'+precedingInputs+'"]').last().after(newReponse.content);
									} else {
										existingSuperbrique.getTbodySelector().prepend(newReponse.content);
									}
									precedingInputs++;
								});
								
								$('.mathquill-update').mathquill('editable').find('textarea').first().focus();
								$('.mathquill-update').removeClass('mathquill-update');
								$.unblockUI();
							},
							error: function() {
								$.unblockUI();
								alert('La requête n\'a pas abouti : erreur lors de l\'opération');
							}
						});
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti');
					}
				});
			} else if (existingBrique.type == 'equations' || existingBrique.type == 'systeme') {
				var dataKey = parseInt(existingBrique.selector.find('div.remove-choice').length) + 1;
				existingBrique.selector.children('div.add-remove-brique-add-choice').append('<div class="position-relative"><div class="radio remove-choice"><span data-key="'+dataKey+'" class="mathquill-editable is-multiple mathquill-update"></span></div></div>');
				$('.mathquill-update').mathquill('editable').find('textarea').focus();
				$('.mathquill-update').removeClass('mathquill-update');
				$.unblockUI();
			} else if (existingBrique.type == 'liste') {
				var dataKey = parseInt(existingBrique.selector.find('li').length) + 1;
				existingBrique.selector.children('div.add-remove-brique-add-choice').children('ul').append('<li class="position-relative"><div class="radio remove-choice"><span data-key="'+dataKey+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></li>');
				$('.mathquill-update').mathquill('textbox').find('textarea').focus();
				$('.mathquill-update').removeClass('mathquill-update');
				$.unblockUI();
			} else if (existingBrique.type == 'liste ordonnee') {
				var dataKey = parseInt(existingBrique.selector.find('li').length) + 1;
				existingBrique.selector.children('div.add-remove-brique-add-choice').children('ol').append('<li class="position-relative"><div class="radio remove-choice"><span data-key="'+dataKey+'" class="mathquill-textbox is-multiple mathquill-update"></span></div></li>');
				$('.mathquill-update').mathquill('textbox').find('textarea').focus();
				$('.mathquill-update').removeClass('mathquill-update');
				$.unblockUI();
			} else {
				$.unblockUI();
			}
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : erreur lors de l\'opération');
		}
	});
})
.on('click', '.delete-choice', function() {
	var thisChoice = $(this);
	var thisChoiceDiv = thisChoice.closest('div.position-relative');
	var existingBrique= new Brique( thisChoice.closest('div.brique') ); 
	if (existingBrique.type == 'liste' || existingBrique.type == 'liste ordonnee') {
		var thisChoiceLi = thisChoice.closest('li.position-relative');
	}
	if (existingBrique.type == 'radio' || existingBrique.type == 'liste deroulante' || existingBrique.type == 'equations' || existingBrique.type == 'systeme') {
		var key = thisChoice.closest('div.radio').children('span[data-key]').attr('data-key');
	} else if (existingBrique.type == 'checkbox') {
		var key = thisChoice.closest('div.checkbox').children('span[data-key]').attr('data-key');
	} else if (existingBrique.type == 'vignettes') {
		var key = thisChoice.closest('div.well').children('span[data-key]').attr('data-key');
	} else if (existingBrique.type == 'liste' || existingBrique.type == 'liste ordonnee') {
		var key = thisChoice.closest('div.radio').children('span[data-key]').attr('data-key');
	}
	var contenuArr = JSON.parse(thisChoice.closest('div.brique').attr('data-brique-contenu'));
	delete contenuArr[key];
	for(var k in contenuArr) {
		if (parseInt(k) > parseInt(key)) {
			contenuArr[k-1] = contenuArr[k];
			delete contenuArr[k];
		}
	}
	thisChoice.closest('div.brique').attr('data-brique-contenu', JSON.stringify(contenuArr));
	$.blockUI({ message: '<h3><span class="hidden-xs">Synchronisation <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
		border: 'none', 
		padding: '15px', 
		backgroundColor: '#000', 
		'-webkit-border-radius': '10px', 
		'-moz-border-radius': '10px', 
		'border-radius': '10px', 
		opacity: .5, 
		color: '#fff' 
	} });
	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'contenu' : existingBrique.selector.attr('data-brique-contenu') },
		url: Routing.generate("majordesk_app_editor_update_brique", {'id_brique' : parseInt(existingBrique.id) }),
		success: function(){
			if (existingBrique.type == 'radio' || existingBrique.type == 'liste deroulante' || existingBrique.type == 'vignettes') {
				var ids_reponse = {};
				var id_reponse = {};
				var k=1;
				$('tr[data-brique-id="'+existingBrique.id+'"]').each(function() {
					id_reponse = {};
					id_reponse["incr"] = -1;
					id_reponse["id_reponse"] = $(this).attr('data-reponse-id');
					ids_reponse[k] = id_reponse;
					k++;
				});
				$.ajax({
					type: "POST",
					dataType: "json",
					data: { 'ids_reponse':JSON.stringify(ids_reponse) },
					url: Routing.generate("majordesk_app_editor_update_reponse_clavier", {'id_reponse' : id_reponse }),
					success: function(data){
						thisChoiceDiv.fadeOut(function(){ $(this).remove() });
						if (existingBrique.type == 'radio' || existingBrique.type == 'liste deroulante') {
							$('div.reponse tr[data-brique-id="'+existingBrique.id+'"]').first().find('select.reponse-contenu-select').children().last().remove();
						} else if (existingBrique.type == 'vignettes'){
							$('div.reponse tr[data-brique-id="'+existingBrique.id+'"]').find('select.reponse-contenu-select').each(function() { $(this).children().last().remove(); });
							var num = parseInt(existingBrique.getPrecedingInputsNumberNotIncluding()) + parseInt(key);
							var reponseSelector = existingBrique.selector.closest('div.superbrique').find('div.reponse').find('tr[data-reponse-numero="'+num+'"]').first();
							removeReponse(reponseSelector);
						}
						$.unblockUI();
					},
					error: function() {
						$.unblockUI();
						alert('La requête n\'a pas abouti : erreur lors de l\'opération');
					}
				});
			} else if (existingBrique.type == 'checkbox') {
				thisChoiceDiv.fadeOut(function(){ $(this).remove() });
				var num = parseInt(existingBrique.getPrecedingInputsNumberNotIncluding()) + parseInt(key);
				var reponseSelector = existingBrique.selector.closest('div.superbrique').find('div.reponse').find('tr[data-reponse-numero="'+num+'"]').first();
				removeReponse(reponseSelector);
				$.unblockUI();
			} else if (existingBrique.type == 'equations' || existingBrique.type == 'systeme') {
				thisChoiceDiv.fadeOut(function(){ $(this).remove() });
				$.unblockUI();
			} else if (existingBrique.type == 'liste' || existingBrique.type == 'liste ordonnee') {
				thisChoiceLi.fadeOut(function(){ $(this).remove() });
				$.unblockUI();
			} else {
				$.unblockUI();
			}
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti : erreur lors de l\'opération');
		}
	});
})


/////////////
// REPONSE //
/////////////

// Identification de réponses

$(document)
.on('focus', '.is-reponse', function() {
	if ( $(this).hasClass('mathquill-editable') ) {
		var id_brique = $(this).closest('tr[data-brique-id]').attr('data-brique-id');
		$(this).closest('div.superbrique').find('div.brique[data-brique-id="'+id_brique+'"]').find('.is-input').addClass('isHighlighted');
	}
	else if ( $(this).hasClass('radio-highlight') ) {
		var id_brique = $(this).closest('tr[data-brique-id]').attr('data-brique-id');
		$(this).closest('div.superbrique').find('div.brique[data-brique-id="'+id_brique+'"]').find('.mathquill-editable').addClass('isHighlighted');
	}
	else if ( $(this).hasClass('checkbox-highlight') ) {
		var num = $(this).closest('tr').attr('data-reponse-numero') - 1;
		$('.is-input').eq(num).find('.mathquill-editable').addClass('isHighlighted');
	}
})
.on('blur', '.is-reponse', function() {
	$('.isHighlighted').removeClass('isHighlighted');
});

//


function Reponse(selector) {		
	this.selector = selector,
	this.id = selector.attr('data-reponse-id'),
	this.numero = parseInt(selector.attr('data-reponse-numero')),
	this.clavier = selector.attr('data-reponse-clavier'),
	this.type = selector.attr('data-reponse-type'),
	
	this.incrementAndRemove = function() {
		if (this.selector.siblings('tr[data-reponse-numero="'+this.numero+'"]').length > 0) {
			this.reorganizeCheckReponseBeforeDeletion();
			this.selector.fadeOut(400, function() { $(this).remove(); });
			this.reorganizeCheckReponseAfterDeletion();
		} else {
			var numero = this.numero;
			this.selector.siblings('tr[data-reponse-numero]').each(function() {
				if( $(this).attr('data-reponse-numero') > numero ) {
					var newNumeroReponse = parseInt($(this).attr('data-reponse-numero')) - 1;
					$(this).attr('data-reponse-numero', newNumeroReponse);
					$(this).find('td:first-child strong').html(newNumeroReponse);
				}
			});
			this.reorganizeCheckReponseBeforeDeletion();
			this.selector.fadeOut(400, function() { $(this).remove(); });
			this.reorganizeCheckReponseAfterDeletion();
		}
	}
	
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
	
	this.reorganizeCheckReponseBeforeDeletion = function() {
		if ( this.selector.find('input[type="checkbox"]:checked').length > 0 )
		{				
			var checkedNumero = this.selector.find('input[type="checkbox"]:checked').attr('data-check-numero');
			if ( this.selector.closest('tbody').find('input[data-check-numero="' + checkedNumero + '"]:checked').length == 2 )
			{			
				// this.selector.siblings('tr[data-numero-reponse="' + this.numero + '"]').each( function() {
					// if ( $(this).find('td:last-child').children(':checked').length == 0 )
					// {
						// $(this).find('input[data-check-numero="' + checkedNumero + '"]').each( function() {
							// $(this).removeAttr('disabled');
						// });
					// }
				// });
				this.selector.closest('tbody').find('input[data-radio-numero = "' + checkedNumero + '"]').parent().remove(); 
				 
				this.selector.closest('tbody').find('input[data-check-numero="' + checkedNumero + '"]').each( function() {
					// if ( $(this).is(':checked') )
					// {
						// $(this).siblings().removeAttr('disabled');
					// }
					$(this).remove();
				});
			}
		}
	}
	this.reorganizeCheckReponseAfterDeletion = function() {
		this.selector.closest('tbody').find('tr').each(function() {
			var i=1;
			$(this).find('input[data-check-numero]').each(function() {
				$(this).attr('data-check-numero',i);
				i++;
			});
		});
		var j=1;
		this.selector.closest('tbody').find('.mapping-type-adjuster').each(function() {
			$(this).find('input[data-radio-numero]').each(function() {
				$(this).attr('data-radio-numero',j);
			});
			j++;
		});
	}
}

function NewReponse(id_superbrique, id_brique, id, numero, clavier, contenu, type, dependances) {
	this.id_superbrique = id_superbrique,
	this.id_brique = id_brique,
	this.id = id,
	this.numero = numero,
	this.clavier = clavier,
	this.contenu = contenu,
	this.type = type,
	this.dependances = dependances;
	
	this.content = '<tr data-superbrique-id="'+id_superbrique+'" data-brique-id="'+id_brique+'" data-reponse-id="'+this.id+'" data-reponse-numero="'+this.numero+'" data-reponse-clavier="'+this.clavier+'" data-reponse-type="'+this.type+'"><td><strong>'+this.numero+'</strong></td>';
	
	if (this.type == 'radio') {
		this.content = this.content + '<td><select name="contenu" class="form-control reponse-contenu-select is-reponse radio-highlight">';
		this.content = this.content +'<option value=""></option>'
		for(var i = 1; i <= 4; i++) {
			this.content = this.content +'<option value="'+i+'">'+i+'</option>'
		}
		this.content = this.content +'</select></td>';
	}
	else if (this.type == 'checkbox') {
		this.content = this.content + '<td> <select name="contenu" class="form-control reponse-contenu-select is-reponse checkbox-highlight">';
		this.content = this.content +'<option value="0">Non cochée</option>';
		this.content = this.content +'<option value="1">Cochée</option>';
		this.content = this.content +'</select></td>';
	}
	else if (this.type == 'liste deroulante') {
		this.content = this.content + '<td><select name="contenu" class="form-control reponse-contenu-select is-reponse radio-highlight">';
		this.content = this.content +'<option value=""></option>'
		for(var i = 1; i <= 4; i++) {
			this.content = this.content +'<option value="'+i+'">'+i+'</option>';
		}
		this.content = this.content +'</select></td>';
	}
	else if (this.type == 'vignette') {
		this.content = this.content + '<td><select name="contenu" class="form-control reponse-contenu-select is-reponse">';
		for(var i = 1; i <= this.clavier; i++) {
			if ( i == this.contenu ) {
				this.content = this.content +'<option value="'+i+'" selected="selected">'+i+'</option>';
			} else {
				this.content = this.content +'<option value="'+i+'">'+i+'</option>';
			}
		}
		this.content = this.content +'</select></td>';
	}
	else if (this.type == 'tableau analyse') {
		this.content = this.content + '<td> - </td>';
	}
	else {
		this.content = this.content + '<td class="position-relative"> <div class="mathquill-editable is-reponse mathquill-update-reponse" data-clavier="'+this.clavier+'"></div> </td>';
	}
	
	this.content = this.content + '<td>';
	
	var repTypes = {'expression exacte':'Expression exacte', 'expression':'Expression', 'triangle':'Triangle', 'angle':'Angle', 'distance':'Distance/Segment'};
	var typeList = '';
	jQuery.each(repTypes, function(key, val) {
		typeList = typeList + '<option value="'+key+'" ';
		if (this.type == key) { typeList = typeList + 'selected="selected"';}
		typeList = typeList + '>'+val+'</option>';
	});
	if (this.type == 'radio') {
		this.content = this.content + '<span class="position-relative-right-17">Radio</span>';
	} else if (this.type == 'checkbox') {
		this.content = this.content + '<span class="position-relative-right-17">Checkbox</span>';
	} else if (this.type == 'vignette') {
		this.content = this.content + '<span class="position-relative-right-17">Vignette</span>';
	} else if (this.type == 'liste deroulante') {
		this.content = this.content + '<span class="position-relative-right-17">Liste déroulante</span>';
	} else if (this.type == 'tableau analyse') {
		this.content = this.content + '<span class="position-relative-right-17">Tableau d\'analyse</span>';
	} else {
		this.content = this.content + '<select class="form-control reponse-type-select" name="type">';
		this.content = this.content + typeList;
		this.content = this.content + '</select>';
	}
	
	this.content = this.content + '<td>'+this.dependances+'</td></tr>';
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
	
	this.addColumn = function() {
		thisNumeroPlus = this.numeroPlus;
		this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]').each( function() {		
			$(this).after(' <input class="dependance-checkbox" data-check-numero="' + thisNumeroPlus + '" type="checkbox"> ');
		});
		var mapping_types = new Array();
			mapping_types[0] = 'association';
			mapping_types[1] = 'association groupe';
			mapping_types[2] = 'permut tot';
		var dependance_radio = '<div class="pull-left mapping-type-adjuster"><br><br>';
			for(var i=0; i<=mapping_types.length-1; i++) {
				var j = i+1;
				dependance_radio = dependance_radio +'<input value="'+mapping_types[i]+'" data-radio-numero="' + this.numero + '" type="radio" name="mapping-radio-type-'+j+'" class="select-mapping-type" /><br>'
			}
			dependance_radio = dependance_radio +'<span class="cursor text-red" rel="tooltip" data-title="Mettre le type à : aucun"><i data-radio-numero="' + this.numero + '" class="icon-remove reset-mapping-type"></i></span></div>';
		
		this.selector.closest('tbody').find('td.dependance_td div:last-child').before(dependance_radio);
	}
	
	this.removeColumn = function() {	
		this.selector.closest('tbody').find('input[data-radio-numero = "' + this.numero + '"]').parent().remove();  
		this.selector.closest('tbody').find('input[data-check-numero="' + this.numero + '"]').each( function() {
			$(this).remove();
		});
	}
}

