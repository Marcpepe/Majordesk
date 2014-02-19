var collectionHolderProf = $('span.profs');
var $addProfLink = $('<button type="button" class="btn btn-info" class="add_prof_link"><i class="icon-plus"></i> Ajouter un professeur</button>');		
function addProfForm(collectionHolderProf, $addProfLink) {
	var prototype = collectionHolderProf.attr('data-prototype');
	var newFormProf = prototype.replace(/__name__/g, collectionHolderProf.children().length + 20);
	$addProfLink.before(newFormProf);
}
$(function() {
	collectionHolderProf.append($addProfLink);
	$addProfLink.on('click', function(e) {
		e.preventDefault();
		addProfForm(collectionHolderProf, $addProfLink);
	});
});
$(document).on('click', '.remove-prof-btn', function() {
	$(this).parent().remove();
});
$(document).on('click', '.erase-prof-btn', function() {
	$(this).parent().remove();
});
