$('.add-chapitre-to-selection').click(function() {
	var id = $(this).attr('data-chapitre-id');
	var nom = $(this).attr('data-chapitre-nom');
	if ( $('li.aucun-devoir').length > 0 ) {
		$('#devoirs').html('<li data-id-chapitre="'+id+'" data-nombre="1">1 exercice dans le chapitre <em>'+nom+'</em></li><input type="hidden" name="chapitre_nb_1" value="1" /><input type="hidden" name="chapitre_1" value="'+id+'" />');
	}
	else if ( $('li[data-id-chapitre="'+id+'"]').length > 0 ) {
		var nbr = parseInt($('li[data-id-chapitre="'+id+'"]').attr('data-nombre'));
		nbr++;
		$('li[data-id-chapitre="'+id+'"]').attr('data-nombre',nbr).html(nbr+' exercices dans le chapitre <em>'+nom+'</em>');
		$('li[data-id-chapitre="'+id+'"]').next('input[name^="chapitre_nb_"]').val(nbr);
	}
	else {
		$('#devoirs').append('<li data-id-chapitre="'+id+'" data-nombre="1">1 exercice dans le chapitre <em>'+nom+'</em></li><input type="hidden" name="chapitre_nb_'+parseInt($('li[data-id-chapitre]').length+1)+'" value="1" /><input type="hidden" name="chapitre_'+parseInt($('li[data-id-chapitre]').length+1)+'" value="'+id+'" />');
	}
});
$('.add-partie-to-selection').click(function() {
	var id = $(this).attr('data-partie-id');
	var nom = $(this).attr('data-partie-nom');
	if ( $('li.aucun-devoir').length > 0 ) {
		$('#devoirs').html('<li data-id-partie="'+id+'" data-nombre="1">1 exercice dans le partie <em>'+nom+'</em></li><input type="hidden" name="partie_nb_1" value="1" /><input type="hidden" name="partie_1" value="'+id+'" />');
	}
	else if ( $('li[data-id-partie="'+id+'"]').length > 0 ) {
		var nbr = parseInt($('li[data-id-partie="'+id+'"]').attr('data-nombre'));
		nbr++;
		$('li[data-id-partie="'+id+'"]').attr('data-nombre',nbr).html(nbr+' exercices dans la partie <em>'+nom+'</em>');
		$('li[data-id-partie="'+id+'"]').next('input[name^="partie_nb_"]').val(nbr);
	}
	else {
		$('#devoirs').append('<li data-id-partie="'+id+'" data-nombre="1">1 exercice dans le partie <em>'+nom+'</em></li><input type="hidden" name="partie_nb_'+parseInt($('li[data-id-partie]').length+1)+'" value="1" /><input type="hidden" name="partie_'+parseInt($('li[data-id-partie]').length+1)+'" value="'+id+'" />');
	}
});
$('.reset-devoirs').click(function() {
	$('#devoirs').html('<li class="aucun-devoir">Aucun devoir pour l\'instant</li>');
});