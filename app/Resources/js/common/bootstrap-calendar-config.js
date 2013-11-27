(function($) {

    "use strict";

	var options = {
		events_url: '/eleve/emploi-du-temps-events',
		view: 'month',
        //day: '2013-03-12',
		//first_day: 2,
		tmpl_path:'../eleve/emploi-du-temps-template-',
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				var timeStamp = new Date().getTime();
				if (val.end > timeStamp) {
					if (val.eleve == 0) {
							var confirmIcon = '';
							if (val.reservation == 1) {
								confirmIcon = '<i class="icon-spinner icon-spin text-orange" rel="tooltip" data-title="En attente d\'une confirmation de ton professeur..."></i>';
							}
							else if (val.reservation == 2) {
								confirmIcon = '<i class="icon-ok text-success" rel="tooltip" data-title="Confirmé par ton professeur"></i>';
							}
							else if (val.reservation == 3) {
								confirmIcon = '<i class="icon-remove text-red" rel="tooltip" data-title="Ton professeur est indisponible pour ce créneau. Programme un autre cours!"></i>';
							}
						if (val.start - 5*3600*1000 > timeStamp) { // plus de 5h avant le cours
							var calEventLink = confirmIcon+' <span rel="tooltip" data-title="'+val.matiere+'" class="label '+val.label+'">'+val.matiere.substring(0,1)+'</span> <a href="#annuler-cours" class="trigger-cours-cancel" data-toggle="modal" data-cours-id="'+val.id+'" rel="tooltip" data-title="Annuler ce cours">'+val.date+' ('+val.heureDebut+'-'+val.heureFin+')</a><br>';
						}
						else {
							var calEventLink = confirmIcon+' <span rel="tooltip" data-title="'+val.matiere+'" class="label '+val.label+'">'+val.matiere.substring(0,1)+'</span> <a class="cursor-forbidden" rel="tooltip" data-title="Il n\'est plus possible d\'annuler ce cours">'+val.date+' ('+val.heureDebut+'-'+val.heureFin+')</a><br>';
						}
						list.append(calEventLink);
					}
					else if (val.professeur == 0) {
							var confirmIcon = '';
							if (val.reservation == 1) {
								confirmIcon = '<i class="icon-spinner icon-spin text-orange" rel="tooltip" data-title="En attente de ta confirmation..."></i>';
							}
							else if (val.reservation == 2) {
								confirmIcon = '<i class="icon-ok text-success" rel="tooltip" data-title="Confirmé"></i>';
							}
							else if (val.reservation == 3) {
								confirmIcon = '<i class="icon-remove text-red" rel="tooltip" data-title="Tu seras indisponible. Contacte ton élève pour lui proposer un autre créneau."></i>';
							}

						var calEventLink = confirmIcon+' <span rel="tooltip" data-title="'+val.matiere+'" class="label '+val.label+'">'+val.matiere.substring(0,1)+'</span> <a href="#confirmer-cours" class="trigger-prof-confirm" data-toggle="modal" data-cours-id="'+val.id+'" rel="tooltip" data-title="Confirmer ta disponibilié pour ce cours">'+val.date+' ('+val.heureDebut+'-'+val.heureFin+')</a><br>';
						list.append(calEventLink);
					}
					else {
						if (val.label!='label-prof-1' && val.label!='label-prof-2' && val.label!='label-prof-3' && val.label!='label-prof-4' && val.label!='label-prof-5') {
								var confirmIcon = '';
								if (val.reservation == 1) {
									confirmIcon = '<i class="icon-spinner icon-spin text-orange" rel="tooltip" data-title="En attente d\'une confirmation du professeur..."></i>';
								}
								else if (val.reservation == 2) {
									confirmIcon = '<i class="icon-ok text-success" rel="tooltip" data-title="Confirmé par le professeur"></i>';
								}
								else if (val.reservation == 3) {
									confirmIcon = '<i class="icon-remove text-red" rel="tooltip" data-title="Ton professeur est indisponible pour ce créneau. Veuillez programmer un autre cours!"></i>';
								}
							if (val.start - 5*3600*1000 > timeStamp) { // plus de 5h avant le cours
								var calEventLink = confirmIcon+' <span rel="tooltip" data-title="'+val.matiere+'" class="label '+val.label+'">'+val.matiere.substring(0,1)+'</span> <a href="#annuler-cours" class="trigger-cours-cancel" data-toggle="modal" data-cours-id="'+val.id+'" rel="tooltip" data-title="Annuler ce cours">'+val.date+' ('+val.heureDebut+'-'+val.heureFin+')</a><br>';
							}
							else {
								var calEventLink = confirmIcon+' <span rel="tooltip" data-title="'+val.matiere+'" class="label '+val.label+'">'+val.matiere.substring(0,1)+'</span> <a class="cursor-forbidden" rel="tooltip" data-title="Il n\'est plus possible d\'annuler ce cours">'+val.date+' ('+val.heureDebut+'-'+val.heureFin+')</a><br>';
							}
							list.append(calEventLink);
						}
					}
				}
			});
		},
		onAfterViewLoad: function(view) {
			$('.calendar-header h3').text(this.title());
			$('.btn-group a').removeClass('active');
			$('a[data-calendar-view="' + view + '"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label'
			}
		}
	};

	var calendar = $('#calendar').calendar(options);

	$('.btn-group a[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group a[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

    $('#first_day').change(function(){
        calendar.set_options({first_day: $(this).val()});
        calendar.view();
    });
}(jQuery));