var chart; // globally available
/* Upload Chapitre Stats */
$(document).on('click', ".chapitre", function() {
	var thisSelector = $(this);
	var id_eleve = parseInt($('#id-eleve').html());
	var id_chapitre = thisSelector.attr('data-id');
	var nom_chapitre = thisSelector.attr('data-nom');
	thisSelector.find('i').addClass('icon-spinner icon-spin').removeClass('icon-chevron-right');
	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_display_chapitre_stats", {'id_eleve' : id_eleve, 'id_chapitre' : id_chapitre}),
		success: function(chapitreStat){
			
			if (chapitreStat.temps_total != undefined) {	
				var tempsTotal = chapitreStat.temps_total;
			}
			else {
				var tempsTotal = ' - ';
			}
			if (chapitreStat.derniere_date !== undefined) {
				var derniereDate = chapitreStat.derniere_date;
			}
			else {
				var derniereDate = ' - ';
			}
			
			var chapitreStats = '<div class="panel panel-default"><div class="panel-heading"><i class="icon-book icon-large text-red pull-left"></i> <b>' + nom_chapitre + '</b></div><div class="panel-body">'+		
								'<ul>'+
									'<li>'+
										'Exercices faits :<br>'+
										'<div id="graph_chapitre" style="height:200px;" ></div>'+
									'</li>'+										
									'<li>Temps total passé sur ce chapitre : <b>' + tempsTotal + '</b></li> <br><br>'+
									'<li>Dernier exercice fait dans ce chapitre : <b>' + derniereDate + '</b></li> <br><br>'+
									'<li>Catégories : <b> - </b></li> <br><br>'+
								'</ul></div>'+
								'<ul class="list-group">'+
									'<a class="list-group-item" href="#"><i class="icon-paper-clip icon-large text-grey"></i> <b>Fiche de cours</b> <i class="icon-external-link position-relative-down-5 pull-right"></i></a></li>'+
									'<a class="list-group-item" href="' + Routing.generate("majordesk_app_parties", {'id_chapitre' : id_chapitre}) + '"><i class="icon-list icon-large text-grey"></i> <b>Liste des exercices</b> <span class="pull-right"><i class="icon-external-link"></i></span></a></li>'+	
								'</ul></div>';
								
			$('#chapitre-stats').fadeOut(function() { 
				$(this).html(chapitreStats).hide().fadeIn(function() {
					if (chapitreStat.pourcent_avec_professeur == 0 && chapitreStat.pourcent_en_autonomie == 0) {
						$('#graph_chapitre').html('<br><br><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span>');
					}
					else {
						/* Chart */
						chart = new Highcharts.Chart({
							chart: {
								renderTo: 'graph_chapitre',
								plotBackgroundColor: null,
								plotBorderWidth: null,
								plotShadow: false
							},
							title: {
								text: ''
							},
							/*tooltip: {
								pointFormat: '{series.name}: <b>{point.percentage}%</b>',
								percentageDecimals: 1
							},*/
							tooltip: {
								formatter: function() {
									return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
								}
							},
							plotOptions: {
								pie: {
									allowPointSelect: true,
									cursor: 'pointer',
									dataLabels: {
										enabled: true,
										color: '#000000',
										connectorColor: '#000000',
										formatter: function() {
											return this.percentage +' %';
										}
									}
								}
							},
							credits: {
								enabled: false
							 },
							series: [{
								type: 'pie',
								name: 'Pourcentage',
								data: [
									{
										name: 'Avec ton professeur',
										y: Math.round(chapitreStat.pourcent_avec_professeur*10)/10,
										color: '#62c462'
									},
									/*{
										name: 'Exercices difficiles',
										y: 0%,
										color: '#ee5f5b'
									},*/
									{
										name: 'En autonomie',
										y: Math.round(chapitreStat.pourcent_en_autonomie*10)/10,
										color: '#006DCC', //0088cc
										sliced: true,
										selected: true
									}
								]
							}]
						});
					}
				});
			});	
			$('.chapitre').closest('a').siblings('a').removeClass('active');
			thisSelector.closest('a').addClass('active');
			thisSelector.find('i').removeClass('icon-spinner icon-spin').addClass('icon-chevron-right');
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});