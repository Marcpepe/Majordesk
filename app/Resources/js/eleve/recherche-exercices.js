/**
  * TYPEAHEAD
  */

// Engine
var SimpleReplacementEngine = {
    compile: function(template) {
        return {
            render: function(context) {
                return template.replace(/\{\{(\w+)\}\}/g,
					function(match, p1) {
						 return jQuery('<div/>').text(context[p1] || '').html();
					}
				);
            }
        };
    }
};
// Recherche
$('#search-exercices').typeahead(
	{
		remote: {
			url: '/eleve/recherche-exercices-%QUERY',
		},
		template: '<p class="col-lg-12 font-size-18 search-{{type}}" data-id="{{id}}"><img src="{{src}}"> <strong class="{{classes}}">{{Type}} :</strong> {{value}}</p>',
		engine: SimpleReplacementEngine,
		limit: 10
	}
);
// Hint size
$('.typeahead.input-lg').siblings('input.tt-hint').addClass('hint-large');


/**
  * SEARCH RESULTS
  */

$(document)
// Parties
.on('click','.search-partie', function() {
	var id_partie = $(this).attr('data-id');
	$.blockUI({ message: '<h3><span class="hidden-xs">Recherche en cours <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
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
		timeout:8000,
		url: Routing.generate("majordesk_app_recherche_exercices_partie", {'id_partie' : id_partie }),
		success: function(result) {	
			$('#parties').html('');
			$('#results').html('');
			var j=0;
			jQuery.each(result, function(i, exercice) {
				var etoiles = '';
				if (exercice.niveau == 0) {
					etoiles = '<i class="icon-star icon-large text-peterriver"></i>';
				} else {
					for (var i=1;i<=parseInt(exercice.niveau);i++) {
						etoiles = etoiles + '<i class="icon-star icon-large text-sunflower"></i>';
					}
				}
				var panel = '<div class="panel panel-default"><div class="panel-heading"><strong>Id :</strong> '+exercice.id+'<span class="pull-right">'+etoiles+'</span><div class="clearfix"></div></div><div class="panel-body no-padding-horizontal">'+exercice.preview+'</div></div>';
				$('#results').append(panel);
				MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
				$('.mathquill-update').mathquill();
				$('.mathquill-update').removeClass('mathquill-update');
				j++;
			});	
			if (j==0) {
				$('#results').html('<div class="col-lg-12 text-pomegranate">Aucun exercice n\'a été trouvé !</div>');
			}
			$.unblockUI();
			// $("html, body").animate({ 
				// scrollTop: $('#results').offset().top + 100
			// }, 1500, 'easeInOutExpo');	
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti. Veuillez rafraîchir la page et réessayer.');
		}
	});
})
// Chapitres
.on('click','.search-chapitre', function() {
	var id_chapitre = $(this).attr('data-id');
	$.blockUI({ message: '<h3><span class="hidden-xs">Recherche en cours <img src="/img/admin/exercices/loading.gif" /></span><span class="visible-xs"><img src="/img/admin/exercices/loading.gif" /></span></h3>', css: { 
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
		timeout:8000,
		url: Routing.generate("majordesk_app_recherche_parties_chapitre", {'id_chapitre' : id_chapitre }),
		success: function(result) {	
			$('#results').html('');
			$('#parties').html('Sélectionner une partie :<br><br>');
			jQuery.each(result, function(i, partie) {
				$('#parties').append('<button class="btn btn-lg btn-default width-100 search-partie" data-id="'+partie.id+'"><span class="pull-left">'+(i+1)+'. '+partie.nom+'</span></button><br>');
			});		
			$('#parties').append('</ul>');
			$.unblockUI();
		},
		error: function() {
			$.unblockUI();
			alert('La requête n\'a pas abouti. Veuillez raffraichir la page et réessayer.');
		}
	});
})
;