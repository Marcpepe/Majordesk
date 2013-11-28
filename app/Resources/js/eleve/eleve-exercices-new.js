/* Loading Maths
 * ============= */

$.blockUI({ message: '<h3><span class="hidden-xs">Chargement <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
	border: 'none', 
	padding: '15px', 
	backgroundColor: '#000', 
	'-webkit-border-radius': '10px', 
	'-moz-border-radius': '10px', 
	'border-radius': '10px', 
	opacity: .5, 
	color: '#fff' 
} });
 
MathJax.Hub.Queue(function () {
	$.unblockUI();
});

/* Feedback
 * ======== */
 
$(document).on('click', '.send-feedback', function() {
	thisButton = $(this);
	var id_exercice = thisButton.attr('data-id-exercice');
	var feedback_type = $('#new-feedback select[name="feedback-type"]').val();
	var feedback_commentaire = $('#new-feedback textarea[name="feedback-commentaire"]').val();
	thisButton.button('loading');
	$.ajax({
		type: "POST",
		// dataType: "json",
		data: { 'type' : feedback_type, 'commentaire' : feedback_commentaire },
		url: Routing.generate("majordesk_app_envoi_feedback", {'id_exercice' : id_exercice}),
		success: function(){
			thisButton.button('reset');
			$('#new-feedback div.modal-footer').html('<div class="alert alert-success"><span class="pull-left">Signalement envoyé. Merci!</span><div class="clearfix"></div></div> <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>');
		},
		error: function() {
			thisButton.button('reset');
			$('#new-feedback div.modal-footer').prepend('<div class="alert alert-danger"><span class="pull-left">Echec de l\'envoi</span><div class="clearfix"></div></div>');
		}
	});
});

 

/* Control Bar
 * =========== */

$('.control-btn').on('click', function (e) {
    $('.control-btn').not(this).popover('hide');
});

$('#control-lock').popover({
			title: '<i class="icon-lock text-mid-grey"></i> S\'entraîner',
			placement: 'bottom',
			container: 'header.navbar',
			html: 'true',		  
});


$(document).on('click', 'button.lock-unlock', function() {
	thisButton = $(this);
	thisButton.button('loading');
	if ( thisButton.hasClass('chapitre-lock') ) {
		var chapitre_only = 1;
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_update_chapitre_only", {'chapitre_only' : chapitre_only}),
			success: function(data){
				thisButton.button('reset');
				$('button.chapitre-lock').addClass('btn-purple').addClass('chapitre-unlock').removeClass('btn-purple-inverted').attr('data-loading-text','Désactivation...').html('<i class="icon-unlock-alt"></i> Désactiver').removeClass('chapitre-lock');

				$('#control-lock').addClass('icon-lock').addClass('text-purple').removeClass('icon-unlock-alt').removeClass('text-mid-grey');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	} 
	else if ( thisButton.hasClass('chapitre-unlock') ) {
		var chapitre_only = 0;
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_update_chapitre_only", {'chapitre_only' : chapitre_only}),
			success: function(data){
				thisButton.button('reset');
				$('button.chapitre-unlock').addClass('btn-purple-inverted').addClass('chapitre-lock').removeClass('btn-purple').attr('data-loading-text','Activation...').html('<i class="icon-lock"></i> Activer').removeClass('chapitre-unlock');
				$('button.partie-unlock').addClass('btn-purple-inverted').addClass('partie-lock').removeClass('btn-purple').attr('data-loading-text','Activation...').html('<i class="icon-lock"></i> Activer').removeClass('partie-unlock');
				$('#control-lock').removeClass('icon-lock').removeClass('text-purple').addClass('icon-unlock-alt').addClass('text-mid-grey');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	}
	else if ( $(this).hasClass('partie-lock') ) {
		var partie_only = 1;
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_update_partie_only", {'partie_only' : partie_only}),
			success: function(data){
				thisButton.button('reset');
				$('button.chapitre-lock').removeClass('btn-purple-inverted').addClass('chapitre-unlock').addClass('btn-purple').attr('data-loading-text','Désactivation...').html('<i class="icon-unlock-alt"></i> Désactiver').removeClass('chapitre-lock');
				$('button.partie-lock').removeClass('btn-purple-inverted').addClass('partie-unlock').addClass('btn-purple').attr('data-loading-text','Désactivation...').html('<i class="icon-unlock-alt"></i> Désactiver').removeClass('partie-lock');
				$('#control-lock').removeClass('icon-lock').removeClass('text-purple').addClass('icon-lock').addClass('text-purple').removeClass('icon-unlock-alt').removeClass('text-mid-grey');
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	} 
	else if ( $(this).hasClass('partie-unlock') ) {
		var partie_only = 0;
		$.ajax({
			type: "POST",
			url: Routing.generate("majordesk_app_update_partie_only", {'partie_only' : partie_only}),
			success: function(data){
				thisButton.button('reset');
				$('button.partie-unlock').addClass('btn-purple-inverted').addClass('partie-lock').removeClass('btn-purple').attr('data-loading-text','Activation...').html('<i class="icon-lock"></i> Activer').removeClass('partie-unlock');
				if (data.chapitre_only == 0) {
					$('#control-lock').removeClass('icon-lock').removeClass('text-purple').addClass('icon-unlock-alt').addClass('text-mid-grey');
				}
			},
			error: function() {
				alert('La requête n\'a pas abouti');
			}
		});
	}
});

$('#control-selection').popover({
			title: '<i class="icon-bullseye text-blue"></i> Devoir à faire',
			placement: 'bottom',
			container: 'header.navbar',
			html: 'true',		  
});

$('#control-favoris').popover({
			title: '<i class="icon-star text-yellow"></i> Mettre de côté cet exercice',
			placement: 'bottom',
			container: 'header.navbar',
			html: 'true',
});

$(document).on('click', 'button.to-favoris', function() {
	$('#control-favoris').addClass('icon-spin')
	var id_exercice = $('button.valider').attr('data-id-exercice');
	var id_matiere = $('#control-navigation').attr('data-id-matiere');
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_exercice_en_favoris", {'id_exercice' : id_exercice}),
		success: function(){
			$('#control-favoris').removeClass('icon-spin').removeClass('text-mid-grey').addClass('text-yellow').attr('data-content',"Cet exercice a été mis de côté.<br><br>Tu pourras le revoir plus tard ou avec ton professeur,<br>en y accédant facilement depuis l'accueil.");
			$('#control-favoris').data('bs.popover').options.content = "Cet exercice a été mis de côté.<br><br>Tu pourras le revoir plus tard ou avec ton professeur,<br>en y accédant facilement depuis l'accueil.";
			$('#control-navigation').attr('data-content',"<a href='"+Routing.generate('majordesk_app_chapitre_selection_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-book'></i> Changer de chapitre</a><br><br><a href='"+Routing.generate('majordesk_app_exercice_next_in_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-forward'></i> Passer à l'exercice suivant</a>");
			$('#control-navigation').data('bs.popover').options.content = "<a href='"+Routing.generate('majordesk_app_chapitre_selection_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-book'></i> Changer de chapitre</a><br><br><a href='"+Routing.generate('majordesk_app_exercice_next_in_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-forward'></i> Passer à l'exercice suivant</a>";
			$('.control-btn').popover('hide');
		},
		error: function() {
			alert('La requête de mise de côté n\'a pas abouti');
		}
	});
});

$('#control-navigation').popover({
			// trigger: 'hover',
			title: '<i class="icon-fast-forward text-mid-grey"></i> Navigation',
			placement: 'bottom',
			container: 'header.navbar',
			html: 'true',
			// delay: { show: 100, hide: 1500 }
});

$(document).on('click', 'a.to-favoris-before-next', function() {
	$('#control-favoris').addClass('icon-spin');
	bootbox.confirm("<i class='icon-warning-sign icon-large text-red'></i> <strong>Confirmation</strong> <br><br>Mettre de côté cet exercice (<i class=\"icon-star text-yellow\"></i>) et passer au suivant ?", 'Non', 'Oui', function(result) {
		if (result) {
			$('.control-btn').popover('hide');
			var id_exercice = $('button.valider').attr('data-id-exercice');
			var id_matiere = $('#control-navigation').attr('data-id-matiere');
			$.ajax({
				type: "POST",
				timeout: 5000,
				url: Routing.generate("majordesk_app_exercice_en_favoris", {'id_exercice' : id_exercice}),
				success: function(){
					$('#control-favoris').removeClass('icon-spin').removeClass('text-mid-grey').addClass('text-yellow').attr('data-content',"Cet exercice a été mis de côté.<br><br>Tu pourras le revoir plus tard ou avec ton professeur,<br>en y accédant facilement depuis l'accueil.");
					$('#control-favoris').data('bs.popover').options.content = "Cet exercice a été mis de côté.<br><br>Tu pourras le revoir plus tard ou avec ton professeur,<br>en y accédant facilement depuis l'accueil.";
					window.location.href = Routing.generate('majordesk_app_exercice_next_in_queue', {'id_matiere':id_matiere});
				},
				error: function() {
					alert('La requête n\'a pas abouti');
				}
			});
		}
		else {
			$('#control-favoris').removeClass('icon-spin');
		}
	});
});


/* Vignettes sortable
 * ================== */

$('.vignettes').sortable();
$('.vignettes').disableSelection();
// $(document).on('click', '.fade-vignette', function() {
	// $(this).closest('li').addClass('hide');
// });		
// $(document).on('click', '.vignettes-reset', function() {
	// var thisUL = $(this).nextAll('ul.vignettes').first();
	// thisUL.children().fadeOut( function() { 
		// thisUL.html(thisUL.prev('div.data-reset').html()); 
	// });
// });		


$(document)
.on('blur', '.mathquill-editable', function() {
	$('.editing').removeClass('editing');
	if (usingSlideOutKeyboard) {
		var $this = $(this);
		$this.addClass('editing');
		setTimeout(function() { $this.find('textarea').focus(); }, 0);
	}
});

/* Temps passé (clock)
 * =================== */

function timer() {
	var ss = $('#clock').attr('data-title').split(':');
	var exercice_clock = new Date();
	exercice_clock.setHours(ss[0]);
	exercice_clock.setMinutes(ss[1]);
	exercice_clock.setSeconds(parseInt(ss[2], '10') + 1);
	$('#clock').attr('data-title', (exercice_clock.getHours()<10?'0':'')+exercice_clock.getHours()+':'+(exercice_clock.getMinutes()<10?'0':'')+exercice_clock.getMinutes()+':'+(exercice_clock.getSeconds()<10?'0':'')+exercice_clock.getSeconds());
	$('#clock').attr('data-original-title', $('#clock').attr('data-title')).tooltip('fixTitle');
	if ( $('#clock').next('.tooltip').is(':visible') ) {
		$('#clock').tooltip('show');
	}
}
function updateTimer() {
	var temps = $('#clock').attr('data-title');
	var id_exercice = $('button.valider').attr('data-id-exercice');
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'temps' : temps },
		url: Routing.generate("majordesk_app_update_temps_exercice", {'id_exercice' : id_exercice})
	});
}

var timerId = setInterval('timer()', 1000);		
var updateTimerId = setInterval('updateTimer()', 60000);

var hidden = "hidden";
var actif = 1;

// Standards:
if (hidden in document) {
	document.addEventListener("visibilitychange", onchange);
}
else if ((hidden = "mozHidden") in document) {
	document.addEventListener("mozvisibilitychange", onchange);
}
else if ((hidden = "webkitHidden") in document) {
	document.addEventListener("webkitvisibilitychange", onchange);
}
else if ((hidden = "msHidden") in document) {
	document.addEventListener("msvisibilitychange", onchange);
}
// IE 9 and lower:
else if ('onfocusin' in document) {
	document.onfocusin = document.onfocusout = onchange;
}
// All others:
// else {
	// // actif = 0;
	// // clearInterval(timerId);	
	// window.onpageshow = window.onpagehide = window.onfocus = window.onblur = onchange;
// }

function onchange () {
	actif = (actif+1)%2;
	if (actif == 0) {
		clearInterval(timerId);	
	}
	else {
		timerId = setInterval("timer()", 1000);
	}
}

/* Tableau d'analyse
 * ================= */
 
 // cell-analyse-input-normal
 // cell-analyse-input-borneg
 // cell-analyse-input-borned
 
 // cell-analyse-signes-entresigne
 // cell-analyse-signes-signe
 // cell-analyse-variations-borneg
 // cell-analyse-variations-borned
 // cell-analyse-variations-fleche
 
 $(document)
.on('blur', '.cell-analyse-input-normal', function() {
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
	rowArr[$(this).closest('td').attr('data-cell-col')].contenu = $(this).mathquill('latex');
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('blur', '.cell-analyse-input-borneg', function() {
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
	rowArr[$(this).closest('td').attr('data-cell-col')].borneg = $(this).mathquill('latex');
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('blur', '.cell-analyse-input-borned', function() {
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[$(this).closest('tr').attr('data-cell-row')].contenu;
	rowArr[$(this).closest('td').attr('data-cell-col')].borned = $(this).mathquill('latex');
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
	
})
 .on('click', '.cell-analyse-signes-signe', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
	if (rowArr[col].contenu == "+" || rowArr[col].contenu == "") {
		rowArr[col].contenu = "-";
		$(this).html('<div class="text-center">$-$</div>');
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	} else if (rowArr[col].contenu == "-") {
		rowArr[col].contenu = "+";
		$(this).html('<div class="text-center">$+$</div>');
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	}
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
 .on('click', '.cell-analyse-signes-entresigne', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
	if (rowArr[col].contenu == "") {
		rowArr[col].contenu = "%valeur-nulle%";
		$(this).html('<div class="text-center">$0$</div>');
		$(this).css('height','100%').css('background-image','url("../img/maths/valeur-nulle.png")').css('background-repeat','repeat-y').css('background-position','center').css('vertical-align','center');
		MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
	} else if (rowArr[col].contenu == "%valeur-nulle%") {
		rowArr[col].contenu = "%valeur-interdite%";
		$(this).html('<div class="text-center"></div>');
		$(this).css('height','100%').css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','center').css('vertical-align','center');
	} else if (rowArr[col].contenu == "%valeur-interdite%") {
		rowArr[col].contenu = "";
		$(this).css('height','auto').css('background-image','none');
	}
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('click', '.cell-analyse-variations-borneg', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
		if (Object.keys(rowArr).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(rowArr).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}

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
		$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].contenu == "%bg-interdite%" && rowArr[col].position == "haut") {
		rowArr[col].position = "bas";
		rowArr[col].contenu = "";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "haut") {
		rowArr[col].position = "bas";
		rowArr[col].contenu = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "bas") {
		rowArr[col].contenu = "%bg-interdite%";
		rowArr[col].position = "haut";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','left').css('vertical-align','center');
		if (flecheType > 2) { $(this).css('font-size','10px'); }
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	}
	
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('click', '.cell-analyse-variations-borned', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
		if (Object.keys(rowArr).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(rowArr).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}

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
		$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].contenu == "%bd-interdite%" && rowArr[col].position == "haut") {
		rowArr[col].position = "bas";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:8px;"></div></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "haut") {
		rowArr[col].position = "bas";
		rowArr[col].contenu = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "bas") {
		rowArr[col].contenu = "%bd-interdite%";
		rowArr[col].position = "haut";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:8px;"></div></div>');
		$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','right').css('vertical-align','center');
		if (flecheType > 2) { $(this).css('font-size','10px'); }
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	}
	
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('click', '.cell-analyse-variations-borne', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
		if (Object.keys(rowArr).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(rowArr).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}

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
		$(this).html('<div class="text-center"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "haut" && rowArr[col].positiond == "haut") {
		rowArr[col].position = "haut";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "bas";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "haut" && rowArr[col].positiond == "bas") {
		rowArr[col].positiong = "bas";
		rowArr[col].positiond = "haut";
		rowArr[col].position = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].contenu == "%valeur-interdite%" && rowArr[col].positiong == "bas" && rowArr[col].positiond == "haut") {
		rowArr[col].positiong = "bas";
		rowArr[col].positiond = "bas";
		rowArr[col].position = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;position:relative;top:'+decalageBas+';"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "haut") {
		rowArr[col].position = "milieu";
		rowArr[col].contenu = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageMilieu+'"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "milieu") {
		rowArr[col].position = "bas";
		rowArr[col].contenu = "";
		$(this).html('<div class="text-center" style="position:relative;top:'+decalageBas+'"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-normal"></span></div>');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	} else if (rowArr[col].position == "bas") {
		rowArr[col].contenu = "%valeur-interdite%";
		rowArr[col].position = "haut";
		rowArr[col].positiong = "haut";
		rowArr[col].positiond = "haut";
		rowArr[col].borneg = "";
		rowArr[col].borned = "";
		$(this).html('<div class="text-center"><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borneg"></span></div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;"><span class="mathquill-editable mathquill-update cell-analyse cell-analyse-input-borned"></span></div></div>');
		$(this).css('height','100%').css('padding',0).css('background-image','url("../img/maths/valeur-interdite.png")').css('background-repeat','repeat-y').css('background-position','center').css('font-size','10px');
		$('.mathquill-update').mathquill('editable');
		$('.mathquill-update').removeClass('mathquill-update');
	}
			
	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
.on('click', '.cell-analyse-variations-fleche', function(){
	var row = parseInt($(this).closest('tr').attr('data-cell-row'));
	var col = parseInt($(this).closest('td').attr('data-cell-col'));
	var contenuArr  =  JSON.parse($(this).closest('table').attr('data-contenu')); 
	var rowArr = contenuArr[row].contenu;
		if (Object.keys(rowArr).length == 4) { var flecheType = 1; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 6) { var flecheType = 2; var decalageBas = '80px'; var decalageMilieu = '40px'; }
		else if (Object.keys(rowArr).length == 8) { var flecheType = 3; var decalageBas = '60px'; var decalageMilieu = '30px'; }
		else if (Object.keys(rowArr).length == 10) { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px'; }
		else { var flecheType = 4; var decalageBas = '40px'; var decalageMilieu = '20px';}
	
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

	$(this).closest('table').attr('data-contenu', JSON.stringify(contenuArr));
})
;
	
/* Validation
 * ========== */
 
$('button.valider').click(function() {
	var boutonValider = $(this);
	boutonValider.button('loading');
	var enteteQuestion = boutonValider.closest('div.question-body').prev();
	enteteQuestion.children('i').after('<i class="icon-spinner icon-spin"></i>').remove();
	// boutonValider.closest('div.question-body').next().fadeOut(400, function() { $(this).html(''); });
	
	var id_exercice = boutonValider.attr('data-id-exercice');
	var id_question = boutonValider.attr('data-id-question');
	var temps = $('#clock').attr('data-title');

	var jsonArr = [];
	var isLastCouche = 0;
	var couche = 0;
	var num_vignette = 1;
	var numero_reponse = 0;
	
	boutonValider.closest('form').find('span[data-couche]').not('.hide').find('.input-eleve').each(function() {
		couche = $(this).closest('span[data-couche]').attr('data-couche');
		if (couche == $(this).closest('span[data-couche]').attr('data-couche-max')) {
			isLastCouche = 1;
		}
		
		if ( $(this).hasClass('mathquill-editable') ) {
			if ($(this).hasClass('case-maths')) {
				numero_reponse = numero_reponse+1;
				console.log('incr de num_repp');
			} else {
				numero_reponse = $(this).attr('data-numero-reponse');
				console.log('pas dincr');
			}
			console.log('ghii');
			jsonArr.push({numero: numero_reponse, contenu: $(this).mathquill('latex')});
		}
		else if ( $(this).attr('type') == 'radio' ) {
			if ( $(this).prop('checked') ) {
				numero_reponse = $(this).attr('data-numero-reponse');
				jsonArr.push({numero: numero_reponse, contenu: $(this).attr('data-radio-numero')});
			}
		}
		else if ( $(this).attr('type') == 'checkbox' ) {
			numero_reponse = $(this).attr('data-numero-reponse');
			jsonArr.push({numero: numero_reponse, contenu: $(this).prop('checked')});
		}
		else if ( $(this).hasClass('liste-deroulante') ) {
			$(this).children('option:selected').each(function() {
				numero_reponse = $(this).attr('data-numero-reponse');
				jsonArr.push({numero: numero_reponse, contenu: $(this).val()});
			});
		}
		else if ( $(this).hasClass('vignette') ) {
			numero_reponse = parseInt($(this).attr('data-numero-shift'))+num_vignette;
			jsonArr.push({numero: parseInt($(this).attr('data-numero-shift'))+num_vignette, contenu: $(this).attr('data-numero-vignette')});
			num_vignette++;
		}
		else if ( $(this).hasClass('tableau-analyse') ) {
			numero_reponse = $(this).attr('data-numero-reponse');
			jsonArr.push({numero: numero_reponse, contenu: $(this).attr('data-contenu')});
		}
	});
	var couche_sup = parseInt(couche) + 1;		
	
	$("html, body").animate({ 
		scrollTop: boutonValider.closest("div.superbrique").offset().top - 100
	}, 1500, 'easeInOutExpo');	
	
	$.ajax({
		type: "POST",
		dataType: "json",
		data: { 'temps' : temps, 'reponses' : JSON.stringify(jsonArr) },
		url: Routing.generate("majordesk_app_validate_reponses", {'id_question' : id_question, 'isLastCouche' : isLastCouche}),
		success: function(data){
			
			if (isLastCouche == 1) {
				enteteQuestion.children('span.badge').html(parseInt(enteteQuestion.children('span.badge').html()) + 1);
				if (data.valeur_question) {
					enteteQuestion.children('i').after('<i class="icon-ok text-success" rel="tooltip" data-title="Terminé"></i>').remove();
					boutonValider.parent().hide();			
					if (data.exercice_termine==1) {
						var id_matiere = $('#intro').attr('data-id-matiere');
						if (data.etape_cours != 2) {
							$('#control-navigation').attr('data-content',"<a href='"+Routing.generate('majordesk_app_chapitre_selection_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-book'></i> Changer de chapitre</a><br><br><a href='"+Routing.generate('majordesk_app_exercice_next_in_queue', {'id_matiere':id_matiere})+"' class='btn btn-default'><i class='icon-forward'></i> Passer à l'exercice suivant</a>");
						}
						setTimeout(function() {$('#control-navigation').popover('show')}, 5000);
					}
				}
				else {
					enteteQuestion.children('i').after('<i class="icon-remove text-red" rel="tooltip" data-title="En cours..."></i>').remove();
				}
			}
			else {
				if (data.valeur_question) {
					boutonValider.closest('form').find('span[data-couche="'+couche+'"]').first().parent().fadeOut(600, function() { $(this).addClass('hide'); boutonValider.closest('form').find('span[data-couche="'+couche_sup+'"]').removeClass('hide').hide().fadeIn(); boutonValider.closest('form').find('span[data-couche="'+couche_sup+'"]').parent().removeClass('hide').hide().fadeIn();  });
					enteteQuestion.children('i').after('<i class="icon-ok text-orange" rel="tooltip" data-title="En cours..."></i>').remove();
				}
				else {
					enteteQuestion.children('span.badge').html(parseInt(enteteQuestion.children('span.badge').html()) + 1);
					enteteQuestion.children('i').after('<i class="icon-remove text-red" rel="tooltip" data-title="En cours..."></i>').remove();
				}
				
			}

			boutonValider.closest('div.question-body').nextAll('.commentaire').html(data.commentaire).hide().fadeIn(); 
			boutonValider.button('reset');
			clearInterval(updateTimerId);
			updateTimerId = setInterval('updateTimer()', 60000);
			if (isLastCouche = 1) {
				MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			}
		},
		error: function() {
			// $("html, body").animate({ scrollTop: boutonValider.closest("div.question-container").offset().top }, 1500);
			enteteQuestion.children('i').after('<i class="icon-exclamation-sign text-grey" rel="tooltip" data-title="Réponse incomplète ou erreur de syntaxe"></i>').remove();
			boutonValider.button('reset');
		}
	});
});