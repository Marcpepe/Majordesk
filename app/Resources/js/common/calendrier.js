$(".timepicker-debut").timepicker({
	minuteStep: 15,
	showInputs: false,
	showMeridian: false,
	defaultTime: '17:00',
	disableFocus: true
});
$(".timepicker-fin").timepicker({
	minuteStep: 15,
	showInputs: false,
	showMeridian: false,
	defaultTime: '19:00',
	disableFocus: true
});
 
$('.datepicker').datepicker({'format' : 'dd/mm/yyyy', 'language' : 'fr', 'weekStart' : '1', 'autoclose' : 'true'});

$('#trigger-reservation-confirm').click(function() {
		var res_matiere = new Array(6);
		res_matiere[1] = 'Mathématiques';
		res_matiere[2] = 'Physique-Chimie';
		res_matiere[3] = 'Biologie';
		res_matiere[4] = 'Anglais';
		res_matiere[5] = 'Français';
		res_matiere[6] = 'Histoire-Géographie';
	$('#reserver-cours #reservation-matiere').html(res_matiere[$('#courstype_matiere').val()]);
		var res_jour = new Array(7);
		res_jour[0]="Dimanche";
		res_jour[1]="Lundi";
		res_jour[2]="Mardi";
		res_jour[3]="Mercredi";
		res_jour[4]="Jeudi";
		res_jour[5]="Vendredi";
		res_jour[6]="Samedi";
		var res_mois = new Array(12);
		res_mois[0]="Janvier";
		res_mois[1]="Février";
		res_mois[2]="Mars";
		res_mois[3]="Avril";
		res_mois[4]="Mai";
		res_mois[5]="Juin";
		res_mois[6]="Juillet";
		res_mois[7]="Août";
		res_mois[8]="Septembre";
		res_mois[9]="Octobre";
		res_mois[10]="Novembre";
		res_mois[11]="Décembre";
		
		var res_date = $('#courstype_dateCours').val();
	    var res_jour_date = new Date(res_date.substring(6),parseInt(res_date.substring(3,5), 10)-1,res_date.substring(0,2));
		console.log(res_jour_date);
	$('#reserver-cours #reservation-date-cours').html(res_jour[res_jour_date.getDay()]+' '+res_jour_date.getDate()+' '+res_mois[res_jour_date.getMonth()]+' '+res_date.substring(6));
	$('#reserver-cours #reservation-heure-debut').html($('#courstype_heureDebut').val());
	$('#reserver-cours #reservation-heure-fin').html($('#courstype_heureFin').val());
});
$(document).on('click', ".trigger-cours-cancel", function() {
	var url = Routing.generate('majordesk_app_calendrier_annuler_event', {'id_event' : $(this).attr('data-cours-id')});
	$('a#annuler-cours-confirm').attr('href', url);
})
.on('click', ".trigger-prof-confirm", function() {
	var url1 = Routing.generate('majordesk_app_calendrier_prof_event', {'id_event' : $(this).attr('data-cours-id'), 'reservation' : 2});
	var url2 = Routing.generate('majordesk_app_calendrier_prof_event', {'id_event' : $(this).attr('data-cours-id'), 'reservation' : 3});
	$('a#confirm-cours-prof').attr('href', url1);
	$('a#refuse-cours-prof').attr('href', url2);
})
.on('click', ".hide-mes-cours", function() {
	$(this).addClass('show-mes-cours').removeClass('hide-mes-cours');
	$('.events-list a[data-eleve="0"]').hide();
})
.on('click', ".show-mes-cours", function() {
	$(this).addClass('hide-mes-cours').removeClass('show-mes-cours');
	$('.events-list a[data-eleve="0"]').show();
})
.on('click', ".hide-cours-prof", function() {
	$(this).addClass('show-cours-prof').removeClass('hide-cours-prof');
	$('.events-list a[data-professeur="'+$(this).attr('data-id-professeur')+'"][data-label^="label-prof-"]').hide();
})
.on('click', ".show-cours-prof", function() {
	$(this).addClass('hide-cours-prof').removeClass('show-cours-prof');
	$('.events-list a[data-professeur="'+$(this).attr('data-id-professeur')+'"][data-label^="label-prof-"]').show();
});

/* Spécifique Parents
 * ================== */

$('#matieresselectortype_eleve').change( function() {
	var id_eleve = $('#matieresselectortype_eleve').val();	
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_populate_matieres", {'id_eleve' : id_eleve}),
		success: function(data){
			$('#matieresselectortype_matiere').html(data.html);
		},
		error: function() {
			alert('La requête n\'a pas abouti');
		}
	});
});

$(document).on('click', ".hide-cours-enfant", function() {
	$(this).addClass('show-cours-enfant').removeClass('hide-cours-enfant');
	$('.events-list a[data-eleve="'+$(this).attr('data-id-eleve')+'"]').hide();
})
.on('click', ".show-cours-enfant", function() {
	$(this).addClass('hide-cours-enfant').removeClass('show-cours-enfant');
	$('.events-list a[data-eleve="'+$(this).attr('data-id-eleve')+'"]').show();
});

$('#trigger-parent-reservation-confirm').click(function() {
		var res_matiere = new Array(6);
		res_matiere[1] = 'Mathématiques';
		res_matiere[2] = 'Physique-Chimie';
		res_matiere[3] = 'Biologie';
		res_matiere[4] = 'Anglais';
		res_matiere[5] = 'Français';
		res_matiere[6] = 'Histoire-Géographie';
	$('#reserver-cours #reservation-matiere').html(res_matiere[$('#matieresselectortype_matiere').val()]);
		var res_jour = new Array(7);
		res_jour[0]="Dimanche";
		res_jour[1]="Lundi";
		res_jour[2]="Mardi";
		res_jour[3]="Mercredi";
		res_jour[4]="Jeudi";
		res_jour[5]="Vendredi";
		res_jour[6]="Samedi";
		var res_mois = new Array(12);
		res_mois[0]="Janvier";
		res_mois[1]="Février";
		res_mois[2]="Mars";
		res_mois[3]="Avril";
		res_mois[4]="Mai";
		res_mois[5]="Juin";
		res_mois[6]="Juillet";
		res_mois[7]="Août";
		res_mois[8]="Septembre";
		res_mois[9]="Octobre";
		res_mois[10]="Novembre";
		res_mois[11]="Décembre";
		
		var res_date = $('#matieresselectortype_dateCours').val();
	    var res_jour_date = new Date(res_date.substring(6),parseInt(res_date.substring(3,5), 10)-1,res_date.substring(0,2));
		console.log(res_jour_date);
	// $('#reserver-cours #reservation-heure-debut').html($('#matieresselectortype_eleve').val()); // ne fonctionne pas
	$('#reserver-cours #reservation-date-cours').html(res_jour[res_jour_date.getDay()]+' '+res_jour_date.getDate()+' '+res_mois[res_jour_date.getMonth()]+' '+res_date.substring(6));
	$('#reserver-cours #reservation-heure-debut').html($('#matieresselectortype_heureDebut').val());
	$('#reserver-cours #reservation-heure-fin').html($('#matieresselectortype_heureFin').val());
});