var chart_week_exercices; // globally available
var chart_week_autonomie; // globally available
var chart_month_exercices; // globally available
var chart_month_autonomie; // globally available
var id_eleve = parseInt($('#id-eleve').attr('data-id-eleve'));
$.ajax({
	type: "POST",
	url: Routing.generate("majordesk_app_display_eleve_stats", {'id_eleve' : id_eleve, 'period' : 1}),
	success: function(eleveStat){	
	
		if (eleveStat.temps_week != undefined) {	
			var tempsWeek = eleveStat.temps_week;
		}
		else {
			var tempsWeek = ' - ';
		}	
		$('#temps-week').html(tempsWeek);
		$('#ex-faits').html(eleveStat.nombre_ex_week);
		$('#ex-valides').html(eleveStat.nombre_ex_week_done);
		$('#nb-moyen').html(eleveStat.nb_moyen_essais);
	
		if (eleveStat.pourcent_avec_professeur_week == 0 && eleveStat.pourcent_en_autonomie_week == 0) {
			$('#graph_semaine_exercices').html('<br><br><div class="text-center"><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span></div>');
		}
		else {
			/* Chart */
			chart_week_exercices = new Highcharts.Chart({
			 chart: {
				renderTo: 'graph_semaine_exercices'
			 },
			 colors: ['#5bc0de'],  //['#62c462', '#006DCC'], // '#ee5f5b' // #0088cc
			 plotOptions: {
				series: {
					animation: { 
					   duration: 500,
					   easing: 'swing'
					},
					colorByPoint: true
				},
			 },
			 // tooltip: {
				// formatter: function() {
					// return '<b>'+ this.series.name +'</b>: '+ this.y +' %';
				// }
			 // },
			 title: {
				text: 'Exercices faits cette semaine'
			 },
			 xAxis: {
				categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
			 },
			 yAxis: {
				title: {
				   text: ''
				},
				// minTickInterval: 1,
				// min: 0,
				// max: 10,
				// labels: {
					// formatter: function() {
						// return this.value +' %';
					// }
				// }
			 },
			 credits: {
				enabled: false
			 },
			 series: [{
				type: 'column',
				name: 'Exercices faits',
				data: [eleveStat.ex_week_array[1],eleveStat.ex_week_array[2],eleveStat.ex_week_array[3],eleveStat.ex_week_array[4],eleveStat.ex_week_array[5],eleveStat.ex_week_array[6],eleveStat.ex_week_array[7]],
				// data: [1,2,5,0,2,0,4],
				showInLegend: false
			 }]
		  });
		}		

		if (eleveStat.pourcent_avec_professeur_week == 0 && eleveStat.pourcent_en_autonomie_week == 0) {
			$('#graph_semaine_autonomie').html('<br><br><div class="text-center"><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span></div>');
		}
		else {
			/* Chart */
			chart_week_autonomie = new Highcharts.Chart({
				chart: {
					renderTo: 'graph_semaine_autonomie',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Type de travail'
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
						// dataLabels: {
							// enabled: true,
							// color: '#000000',
							// connectorColor: '#000000',
							// formatter: function() {
								// return this.percentage +' %';
							// }
						// }
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
							name: 'Avec le professeur',
							y: Math.round(eleveStat.pourcent_avec_professeur_week*10)/10,
							color: '#62c462'
						},
						{
							name: 'En autonomie',
							y: Math.round(eleveStat.pourcent_en_autonomie_week*10)/10,
							color: '#006DCC', //0088cc
							sliced: true,
							selected: true
						}
					]
				}]
			});
		}
	},
	error: function() {
		alert('Les graphes ne peuvent pas s\'afficher correctement.');
	}
});

$.ajax({
	type: "POST",
	url: Routing.generate("majordesk_app_display_eleve_stats", {'id_eleve' : id_eleve, 'period' : 2}),
	success: function(eleveStat){	
	
		if (eleveStat.temps_month != undefined) {	
			var tempsMonth = eleveStat.temps_month;
		}
		else {
			var tempsMonth = ' - ';
		}	
		$('#temps-month').html(tempsMonth);
		$('#ex-faits-month').html(eleveStat.nombre_ex_month);
		$('#ex-valides-month').html(eleveStat.nombre_ex_month_done);
		$('#nb-moyen-month').html(eleveStat.nb_moyen_essais);
	
		if (eleveStat.pourcent_avec_professeur_month == 0 && eleveStat.pourcent_en_autonomie_month == 0) {
			$('#graph_mois_exercices').html('<br><br><div class="text-center"><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span></div>');
		}
		else {
			// Préparation
			var categories = [];
			var data = [];
			for (var i=1;i<=Object.keys(eleveStat.ex_month_array).length;i++) {
				categories.push(i);
				data.push(eleveStat.ex_month_array[i]);
			}
		
			/* Chart */
			chart_month_exercices = new Highcharts.Chart({
			 chart: {
				renderTo: 'graph_mois_exercices'
			 },
			 colors: ['#5bc0de'],
			 plotOptions: {
				series: {
					animation: {
					   duration: 500,
					   easing: 'swing'
					},
					colorByPoint: true
				},
			 },
			 title: {
				text: 'Exercices faits cette semaine'
			 },
			 xAxis: {
				categories: categories,
				labels: {
					rotation: -90,
                    align: 'right'
				}
			 },
			 yAxis: {
				title: {
				   text: ''
				},
			 },
			 credits: {
				enabled: false
			 },
			 series: [{
				type: 'column',
				name: 'Exercices faits',
				data: data,
				showInLegend: false
			 }]
		  });
		}		

		if (eleveStat.pourcent_avec_professeur_month == 0 && eleveStat.pourcent_en_autonomie_month == 0) {
			$('#graph_mois_autonomie').html('<br><br><div class="text-center"><span style="font-size: 30px;"><i class="icon-bar-chart icon-4x text-light-grey pull-left"></i></span></div>');
		}
		else {
			/* Chart */
			chart_month_autonomie = new Highcharts.Chart({
				chart: {
					renderTo: 'graph_mois_autonomie',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Type de travail'
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
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
							name: 'Avec le professeur',
							y: Math.round(eleveStat.pourcent_avec_professeur_month*10)/10,
							color: '#62c462'
						},
						{
							name: 'En autonomie',
							y: Math.round(eleveStat.pourcent_en_autonomie_month*10)/10,
							color: '#006DCC',
							sliced: true,
							selected: true
						}
					]
				}]
			});
		}
	},
	error: function() {
		alert('Les graphes ne peuvent pas s\'afficher correctement.');
	}
});