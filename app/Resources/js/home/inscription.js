$(document).on('click', ".timepicker-debut", function() {
	$(this).timepicker({
		minuteStep: 15,
		showInputs: false,
		showMeridian: false,
		defaultTime: '16:00',
		disableFocus: true
	});
});

var collectionHolderDisponibilite = $('span.disponibilites');
var $addDisponibiliteLink = $('<button type="button" class="btn btn-info" class="add_disponibilite_link"><i class="icon-plus"></i> Ajouter une disponibilité</button>');		
function addDisponibiliteForm(collectionHolderDisponibilite, $addDisponibiliteLink) {
	var prototype = collectionHolderDisponibilite.attr('data-prototype');
	var newForm = prototype.replace(/__name__/g, collectionHolderDisponibilite.children().length + 20);
	$addDisponibiliteLink.before(newForm);
}
$(function() {
	collectionHolderDisponibilite.append($addDisponibiliteLink);
	$addDisponibiliteLink.on('click', function(e) {
		e.preventDefault();
		addDisponibiliteForm(collectionHolderDisponibilite, $addDisponibiliteLink);
	});
});
$(document).on('click', ".remove-disponibilite-btn", function() {
	$(this).closest('span').remove();
});
var lycees = new Array('Collège Jean-Baptiste Poquelin', 'Collège César Franck', 'Collège Montgolfier', 'Collège Pierre-Jean de Béranger', 'Collège Jules Romains', 'Collège La Salla Notre-Dame de la Gare', 'Collège Thomas Mann', 'Collège Octave Gréard', 'Collège des Bernardins', 'Collège François Couperin', 'Collège de France', 'Collège Pierre Alviset', 'Collège Bernard Palissy', 'Collège de la Grange aux Belles', 'Collège Louise Michel', 'Collège Valmy', 'Collège Raymond Queneau', 'Ecole normale israélite orientale', 'Collège Rognoni', 'Lycée Jacques Monod', 'Lycée Saint-Suplice', 'Lycée Lucas de Nehou', 'Collège Alain Fournier', 'Collège Anne Franck', 'Collège Beaumarchais', 'Collège Lucie et Raymond Aubrac', 'Collège Pilâtre de Rozier', 'Collège Jacques Prévert', 'Collège Stanislas', 'Collège Guillaume Budé', 'Collège Mozart', 'Collège Saint-Georges', 'Collège Jean-Baptiste Clément', 'Collège Robert Doisneau', 'Institutions scolaires du Beth Loubavitch', 'Lycée N\'R Hatorah', 'Lycée Jacquard', 'Lycée Jules Richard', 'Lycée Beth Yacov', 'Lycée Henri Bergson', 'Lycée Sinaï', 'Collège Edouard-Pailleron', 'Lycée François Rabelais', 'Lycée Fidès', 'Lycée Hattemer', 'Collège Paul Gauguin', 'Lycée Morvan', 'Lycée Colbert', 'Lycée Jules Siegfried', 'Lycée Paul-Valéry', 'Lycée François Villon', 'Lycée ESAA Ecole Boulle', 'Ecole active bilingue Jeannine Manuel (EAB)', 'Lycée Louis Le Grand', 'Lycée Saint Louis', 'Lycée Henri IV', 'Lycée privé saint-Jean de Passy', 'Lycée Moria-Diane Benvenuti', 'Lycée autogéré de Paris', 'Lycée privé Blomet', 'Lycée privé Bossuet Notre-Dame', 'Lycée privé Stanislas', 'Lycée privé Fénelon Sainte-Marie', 'Lycée Hôtelier Guillaume Tirel', 'Lycée privé La Rochefoucauld', 'Lycée privé saint-Louis de Gonzague', 'Lycée privé Yabné', 'Lycée privé de la Tour', 'International School of Paris', 'Lycée Edgar Quinet', 'Lycée privé Edgar Poe', 'Lycée privé Notre-Dame de Sion', 'Lycée privé saint-Michel de Picpus', 'Lycée privé Lucien de Hirsch', 'Lycée Gaston Tenoudji', 'Lycée privé Paul Claudel', 'Lycée privé Sinaï', 'Lycée privé de l\'Alma', 'Lycée privé Notre-Dame de France', 'Lycée privé saint-Thomas d\'Aquin', 'Lycée privé Beth Hanna', 'Lycée privé Pascal', 'Lycée Condorcet', 'Lycée Lavoisier', 'Lycée privé des Francs Bourgeois', 'Lycée Charlemagne', 'Lycée privé L\'Ecole alsacienne', 'Lycée privé Charles Péguy', 'Lycée Victor Duruy', 'Lycée privé Rocroy Saint-Vincent-de-Paul', 'Lycée privé de l\'Assomption (Lubeck)', 'Lycée privé Sainte-Ursule Louise de Bettignies', 'Lycée privé saint-Michel des Batignolles', 'Lycée privé Charles de Foucauld', 'Lycée privé Sainte-Elisabeth', 'Lycée privé Sainte-Geneviève', 'Lycée privé Le Rebours', 'Lycée privé Gerson', 'Lycée Chaptal', 'Lycée privé Massillon', 'Lycée Jean-Baptiste Say', 'Lycée privé Georges Leven', 'Lycée Jean de La Fontaine', 'Lycée privé Eugène Napoléon - saint-Pierre Fourier', 'Lycée privé Passy saint-Honoré', 'Lycée régional du bâtiment et des travaux publics', 'Lycée Sophie Germain', 'Lycée privé Sévigné', 'Ecole active bilingue de l\'Etoile (EAB)', 'Lycée privé Louise de Marillac', 'Lycée privé Albert de Mun', 'Lycée privé Notre-Dame des Oiseaux', 'Lycée privé Sainte-Jeanne Elisabeth', 'Lycée privé saint-Nicolas', 'Lycée Hélène Boucher', 'Lycée Carnot', 'Lycée privé Carcado Saisseval', 'Lycée Janson de Sailly', 'public - Paris 75016', 'Lycée Buffon', 'Lycée privé d\'Hulst', 'Lycée Fénelon', 'Lycée Racine', 'Lycée privé Ozar Hatorah', 'Lycée Jean DROUANT', 'Lycée Claude Monet', 'Lycée Victor Hugo', 'Lycée privé saint-Sulpice', 'Lycée polyvalent Dorian', 'Lycée Molière', 'Lycée international de Paris Honoré de Balzac', 'Lycée Georges Brassens', 'Lycée Maximilien Vox', 'Lycée Claude Bernard', 'Lycée Arago', 'Lycée Gabriel Fauré', 'Lycée privé des Petits Champs', 'Lycée technologique d\'Arts appliqués Auguste Renoir', 'Lycée technique privé de l\'école technique supérieure du laboratoire', 'Lycée Jacques Decour', 'Lycée polyvalent privé Notre-Dame (saint-Vincent-de-Paul)', 'Lycée privé Heikhal Menahem Sinaï', 'Lycée Maurice Ravel', 'Lycée privé Thérèse Chappuis', 'Lycée Montaigne', 'Lycée Pétrelle', 'Lycée Alphonse de Lamartine', 'Lycée Turgot', 'Lycée Jules Ferry', 'Lycée privé Catherine Labouré', 'Lycée Pierre-Gilles de Gennes - Ecole nationale de chimie, physique et biologie', 'Lycée Simone Weil', 'Lycée Jean Lurçat - Site Gobelins', 'Lycée Diderot', 'Lycée Emile Dubois', 'Lycée Camille Sée', 'Lycée Rodin', 'Lycée Paul Bert', 'Lycée Voltaire');
$('.lycee-typeahead').typeahead({ 
	name : 'schools',
	local : lycees, 
	limit : 8 
});

// Options

// Avec Plateforme
$('.choix-situation').slideUp(0);
$('#no-pack').hide();

$('.option-text').slideUp(0);
$('.option-details').click(function(){
	$('.option-text').slideToggle();
	if ( $('i.icon-plus-sign').length > 0 ) {
		$('i.icon-plus-sign').addClass('icon-minus-sign').removeClass('icon-plus-sign');
	}
	else {
		$('i.icon-minus-sign').addClass('icon-plus-sign').removeClass('icon-minus-sign');
	}
});
$('.option-select').click(function(){
	$('.situation.panel-primary').removeClass('panel-primary');
	$(this).closest('div.panel').addClass('panel-primary');
});

if ( $('input.matiere').is(':checked') == false ) {
	$('#packs-1').slideUp(0);
	$('#packs-2').slideUp(0);
	$('#packs-3').slideUp(0);
}
else {
	$('#no-matiere').hide();
}

$('.option-1').click(function(){
	if ( $('.choix-matiere').find('input.matiere:checked').length > 0 ) {
		$('#no-pack').hide();
		$('#packs-3').slideUp();
		$('#packs-1').slideDown();
	}
});
$('.option-3').click(function(){
	if ( $('.choix-matiere').find('input.matiere:checked').length > 0 ) {
		$('#no-pack').hide();
		$('#packs-1').slideUp();
		$('#packs-3').slideDown();
	}
});

$('input.matiere').on('click', function() {
	if ($(this).is(':checked') ) {
		$('#no-matiere').hide();
		if ( $(this).hasClass('maths') ) {
			$('#packs-2').slideUp();
			$('.choix-situation').slideDown();
			$('#no-pack').show();
		} else {
			if ($('input.matiere.maths').is(':checked') == false) {
				$('#packs-2').slideDown();
			}
		}
	}
	else {
		$('.situation.panel-primary').removeClass('panel-primary');
		if ( $('input.matiere').is(':checked') == false ) {
			if ( $(this).hasClass('maths') ) {
				$('.choix-situation').slideUp( function() {$('#no-pack').hide(); $('#no-matiere').show();} );
				$('#packs-1').slideUp();
				$('#packs-3').slideUp();
			} else {
				$('#packs-2').slideUp(function() { $('#no-matiere').show(); });
			}
		}
		else {
			if ( $(this).hasClass('maths') ) {
				$('.choix-situation').slideUp( function() {$('#no-pack').hide(); $('#no-matiere').hide();} );
				$('#packs-1').slideUp();
				$('#packs-3').slideUp();
				$('#packs-2').slideDown();
			}
		}
	}
});


// Sans Plateforme
if ( $('input.matiere-no-plateforme').is(':checked') == false ) {
	$('#packs-2').slideUp(0);
}
else {
	$('#no-matiere-no-plateforme').hide();
}
$('input.matiere-no-plateforme').on('click', function() {
	if ($(this).is(':checked') ) {
		$('#no-matiere-no-plateforme').hide();
		$('#packs-2').slideDown();	
	}
	else {
		if ( $('input.matiere-no-plateforme').is(':checked') == false ) {
			$('#packs-2').slideUp(function() {$('#no-matiere-no-plateforme').show();});
		}
	}
});

// Commun
$('input[name="pack"][type="checkbox"]').change(function() {
	$('.pack-complet').removeClass('panel-primary');
	$('.pack-decouverte').removeClass('panel-primary');
	if ($(this).is(':checked') ) {
		$(this).closest('div.panel').addClass('panel-primary');
		$('.validation-pack').remove();
		var validation_pack = '<span class="validation-pack"><button type="submit" class="btn btn-success pull-right validation-btn">Valider <i class="icon-angle-right icon-large"></i></button><br><br></span>';
		$(this).closest('div.panel-body').append(validation_pack);
	}
	else {
		$(this).closest('div.panel').removeClass('panel-primary');
		$('.validation-pack').remove();
	}
	$('input[name="pack"][type="radio"]').prop('checked', false);
	
	
});

$('input[name="pack"][type="radio"]').change(function() {
	$('.pack-complet').removeClass('panel-primary');
	$('.pack-decouverte').removeClass('panel-primary');
	$('.pack-plateforme').removeClass('panel-primary');
	$(this).closest('div.panel').addClass('panel-primary');
	
	$('.validation-pack').remove();
	var validation_pack = '<span class="validation-pack"><button type="submit" class="btn btn-success pull-right validation-btn">Valider <i class="icon-angle-right icon-large"></i></button><br><br></span>';
	$(this).closest('div.panel-body').append(validation_pack);
	$('input[name="pack"][type="checkbox"]').prop('checked', false);
});

