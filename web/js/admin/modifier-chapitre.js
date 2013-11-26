var collectionHolderAbonnements = $('span.abonnements');
var $addAbonnementLink = $('<button type="button" class="btn btn-info" class="add_abonnement_link"><i class="icon-plus"></i></button>');		
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
	$(this).parent().remove();
});