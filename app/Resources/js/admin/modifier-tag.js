var tags = new Array();
$('span.one-tag').each(function() {
	tags.push($(this).attr('data-nom-tag'));
});
$('.tag-typeahead').typeahead({ source : tags, items : 15 });

$('#supprimer-tag').click(function(e) {
	e.preventDefault();
	var href = $(this).attr('href');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-yellow'></i> <strong>Confirmation</strong> <br><br>Souhaitez-vous supprimer ce tag ? <br>(Attention, tous les tags enfant seront supprimés)", 'Non', 'Oui', function(result) {
		if(result) { window.location.href = href }	
	});
})

var collectionHolderPTag = $('span.ptags');
var $addPTagLink = $('<button type="button" rel="tooltip" data-title="Ajouter un Tag parent" class="btn btn-info" class="add_ptag_link"><i class="icon-plus"></i></button>');		
function addPTagForm(collectionHolderPTag, $addPTagLink) {
	var prototype = collectionHolderPTag.attr('data-prototype');
	var newFormPTag = prototype.replace(/__name__/g, collectionHolderPTag.children().length + 20);
	$addPTagLink.before(newFormPTag);
}
$(function() {
	collectionHolderPTag.append($addPTagLink);
	$addPTagLink.on('click', function(e) {
		e.stopPropagation();
		addPTagForm(collectionHolderPTag, $addPTagLink);
		$('.tag-typeahead').typeahead({ source : tags, items : 15 });
	});
});
var collectionHolderCTag = $('span.ctags');
var $addCTagLink = $('<button type="button" rel="tooltip" data-title="Ajouter un Tag enfant" class="btn btn-info" class="add_ctag_link"><i class="icon-plus"></i></button>');		
function addCTagForm(collectionHolderCTag, $addCTagLink) {
	var prototype = collectionHolderCTag.attr('data-prototype');
	var newFormCTag = prototype.replace(/__name__/g, collectionHolderCTag.children().length + 20);
	$addCTagLink.before(newFormCTag);
}
$(function() {
	collectionHolderCTag.append($addCTagLink);
	$addCTagLink.on('click', function(e) {
		e.preventDefault();
		addCTagForm(collectionHolderCTag, $addCTagLink);
	});
});
$(document).on('click', '.remove-tag-btn', function() {
	$(this).parent().remove();
});