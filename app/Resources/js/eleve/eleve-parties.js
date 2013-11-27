var chart; // globally available
/* Upload Chapitre */
$(document).on('click', ".partie", function() {
	var thisSelector = $(this);
	var id_eleve = parseInt($('#id-eleve').html());
	var id_partie = thisSelector.attr('data-id');
	var nom_partie = thisSelector.next('div').html();
	thisSelector.find('i').addClass('icon-spinner icon-spin').removeClass('icon-chevron-right');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_display_partie_stats", {'id_eleve' : id_eleve, 'id_partie' : id_partie}),
		success: function(partieStat){
			
			var partieStats = '<div class="panel panel-default panel-default"><div class="panel-heading"><i class="icon-book icon-large text-red pull-left"></i> <b>' + nom_partie + '</b></div><div class="panel-body">'+		
								'<ul>'+
									'<li>'+
										'Avancement :<br><br>'+
										'<div id="graph_partie" style="height:200px;" ></div>'+
									'</li><br>'+										
									'<li>Notions importantes dans cette partie : <b> - </b></li><br><br>'+
								'</ul></div>'+
								'<ul class="list-group">';
			if ( thisSelector.attr('data-vide') != 0 ) {
				partieStats = partieStats + '<a class="list-group-item" href="' + Routing.generate("majordesk_app_exercice_aleatoire_partie", {'id_partie' : id_partie}) + '"><i class="icon-list icon-large text-grey"></i> <b>S\'entrainer dans cette partie</b> <span class="pull-right"><i class="icon-external-link"></i></span></a></ul></div>';
			}
			else {
				partieStats = partieStats + '<a class="list-group-item cursor-forbidden" rel="tooltip" data-title="Partie indisponible pour le moment..."><i class="icon-list icon-large text-grey"></i> <b>S\'entrainer dans cette partie</b> <span class="pull-right"><i class="icon-external-link"></i></span></a></ul></div>';
			}
									
								
			$('#partie-stats').fadeOut(function() {
				$(this).html(partieStats).hide().fadeIn(function() {
					if (partieStat.pourcent_avec_professeur == 0 && partieStat.pourcent_en_autonomie == 0) {
						$('#graph_partie').html('<br><br><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span>');
					}
					else {
						/* Chart */
						chart = new Highcharts.Chart({
						 chart: {
							renderTo: 'graph_partie',
						 },
						 colors: ['#62c462', '#006DCC'], // '#ee5f5b' // #0088cc
						 plotOptions: {
							series: {
								animation: {
								   duration: 500,
								   easing: 'swing'
								},
								colorByPoint: true
							},
						 },
						 tooltip: {
							formatter: function() {
								return '<b>'+ this.series.name +'</b>: '+ this.y +' %';
							}
						 },
						 title: {
							text: ''
						 },
						 xAxis: {
							categories: ['Faits avec ton professeur', 'Faits en autonomie']
						 },
						 yAxis: {
							title: {
							   text: ''
							},
							minTickInterval: 10,
							min: 0,
							max: 100,
							labels: {
								formatter: function() {
									return this.value +' %';
								}
							}
						 },
						 credits: {
							enabled: false
						 },
						 series: [{
							type: 'column',
							name: 'Exercices faits',
							data: [partieStat.pourcent_avec_professeur*100, partieStat.pourcent_en_autonomie*100],
							showInLegend: false
						 }]
					  });
					}
				});
			});					
			$('.partie').siblings('a').removeClass('active');
			thisSelector.addClass('active');
			thisSelector.find('i').removeClass('icon-spinner icon-spin').addClass('icon-chevron-right');
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});