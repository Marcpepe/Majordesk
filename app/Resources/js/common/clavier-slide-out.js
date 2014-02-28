/**
  * Clavier slide out
  *
  **/

var usingSlideOutKeyboard = false;
  
$(document)
.on('focusin', '.mathquill-editable', function() {
	$('#cbp-clavier').addClass('cbp-clavier-open');
	if (usingSlideOutKeyboard == false) {
		if ($(this).attr('data-clavier')) {
			selectClavier($(this).attr('data-clavier'));
		}
	}
})
.on('focusout', '.mathquill-editable', function() {
	if (usingSlideOutKeyboard == false) {
		$('#cbp-clavier').removeClass('cbp-clavier-open');
	}
})
.on('mouseenter', '#cbp-clavier', function() {
	usingSlideOutKeyboard = true;
})
.on('mouseleave', '#cbp-clavier', function() {
	usingSlideOutKeyboard = false;
})
.on('click', '.touche', function() {
	// $(this).mousedown(function(e) { e.stopImmediatePropagation(); return false; }); // éviter le clignotement ?
	var left = parseInt($(this).attr('left'));
	var latex = $(this).attr('latex');
	$('.editing').mathquill('write', latex).find('textarea').focus();
	if (left) {
		e = jQuery.Event("keydown");
		e.which = 37;
		e.keyCode = 37;
		for (i = 0; i < left; i++) {
			$('.editing').trigger(e);
		}
	}
})
.on('click', '.cbp-tile[data-clavier-numero]', function() {
	selectClavier($(this).attr('data-clavier-numero'));
})
.on('click', '.cbp-slide-left', function() {
	var nombreTiles = 5;
	var current = parseInt($('.cbp-tile.active').attr('data-clavier-numero'));
	var requested = current-1;
	if (requested == 0) {
		requested = nombreTiles;
	}
	selectClavier(requested);
})
.on('click', '.cbp-slide-right', function() {
	var nombreTiles = 5;
	var current = parseInt($('.cbp-tile.active').attr('data-clavier-numero'));
	var requested = current+1;
	if (requested == nombreTiles+1) {
		requested = 1;
	}
	selectClavier(requested);
})
;

function selectClavier(requested) {
	$('.cbp-tile').removeClass('active');
	$('.cbp-tile[data-clavier-numero="'+requested+'"]').addClass('active');
	var valeur = (parseInt(requested)-1) * 104;
	var unites = 'px';
	$('.cbp-children').animate({'right':valeur+unites});
}