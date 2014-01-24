$(document)
.on('click', ".add_disponibilite_link", function() {
	$(".timepicker-debut").timepicker({
		minuteStep: 15,
		showInputs: false,
		showMeridian: false,
		defaultTime: '16:00',
		disableFocus: true
	});
	$(".timepicker-fin").timepicker({
		minuteStep: 15,
		showInputs: false,
		showMeridian: false,
		defaultTime: '23:00',
		disableFocus: true
	});
});
 
$('.datepicker').datepicker({'format' : 'dd/mm/yyyy', 'language' : 'fr', 'weekStart' : '1', 'autoclose' : 'true'});

var collectionHolderAbonnements = $('span.abonnements');
var $addAbonnementLink = $('<button type="button" class="btn btn-info"><i class="icon-plus"></i> Ajouter une matière</button>');		
function addAbonnementForm(collectionHolderAbonnements, $addAbonnementLink) {
	var prototype = collectionHolderAbonnements.attr('data-prototype');
	var newForm = prototype.replace(/__name__/g, collectionHolderAbonnements.children().length + 20);
	$addAbonnementLink.before(newForm);
}
$(function() {
	collectionHolderAbonnements.append($addAbonnementLink);
	$addAbonnementLink.on('click', function(e) {
		e.preventDefault();
		addAbonnementForm(collectionHolderAbonnements, $addAbonnementLink);
	});
});
$(document).on('click', ".remove-abonnement-btn", function() {
	$(this).parent().parent().remove();
});

var collectionHolder = $('span.disponibilites');
var $addDisponibiliteLink = $('<button type="button" class="btn btn-info add_disponibilite_link"><i class="icon-plus"></i> Ajouter une disponibilité</button>');		
function addDisponibiliteForm(collectionHolder, $addDisponibiliteLink) {
	var prototype = collectionHolder.attr('data-prototype');
	var newForm = prototype.replace(/__name__/g, collectionHolder.children().length + 20);
	$addDisponibiliteLink.before(newForm);
}
$(function() {
	collectionHolder.append($addDisponibiliteLink);
	$addDisponibiliteLink.on('click', function(e) {
		e.preventDefault();
		addDisponibiliteForm(collectionHolder, $addDisponibiliteLink);
	});
});
$(document).on('click', ".remove-disponibilite-btn", function() {
	$(this).parent().parent().remove();
});