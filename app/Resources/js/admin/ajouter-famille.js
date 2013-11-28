$(function() {
	$(document).on('click', ".timepicker-alerte", function() {
		$(this).timepicker({
			minuteStep: 15,
			showInputs: false,
			showMeridian: false,
			defaultTime: '19:00',
			disableFocus: true
		});
	});
});
var collectionHolder = $('span.parents');
var $addParentLink = $('<button type="button" class="btn btn-info" class="add_parent_link"><i class="icon-plus"></i> Ajouter un Parent</button>');
function addParentForm(collectionHolder, $addParentLink) {
	var prototype = collectionHolder.attr('data-prototype');
	var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
	$addParentLink.before(newForm);
}
$(function() {
	collectionHolder.append($addParentLink);
	$addParentLink.on('click', function(e) {
		e.preventDefault();
		addParentForm(collectionHolder, $addParentLink);
	});
});
$(document).on('click', ".remove-parent-btn", function() {
	$(this).parents(".remove-parent-div").remove();
});