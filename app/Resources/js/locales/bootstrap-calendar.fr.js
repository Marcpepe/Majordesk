var language = {
	error_noview: 'Calendrier: Vue {0} introuvable',
	error_dateformat: 'Calendrier: Format de date incorrect {0}. Formats acceptés : "now" ou "yyyy-mm-dd"',
	error_loadurl: 'Calendrier: Events load URL is not set',
	error_where: 'Calendrier: Mauvaise commande de navigation {0}. Commandes acceptées : "suivant", "précédent" or "aujourd\'hui"',

	title_year: 'Année {0}',
	title_month: '{0} {1}',
	title_week: 'Semaine {0}',
	title_day: '{0} {1} {2} {3}',

	week:'Semaine',

	m0: 'Janvier',
	m1: 'Février',
	m2: 'Mars',
	m3: 'Avril',
	m4: 'Mai',
	m5: 'Juin',
	m6: 'Juillet',
	m7: 'Août',
	m8: 'Septembre',
	m9: 'Octobre',
	m10: 'Novembre',
	m11: 'Décembre',

    ms0: 'Jan',
    ms1: 'Fév',
    ms2: 'Mar',
    ms3: 'Avr',
    ms4: 'Mai',
    ms5: 'Jun',
    ms6: 'Jul',
    ms7: 'Aoû',
    ms8: 'Sep',
    ms9: 'Oct',
    ms10: 'Nov',
    ms11: 'Déc',

	d0: '<span class="hidden-xs">Dimanche</span><span class="visible-xs">D</span>',
	d1: '<span class="hidden-xs">Lundi</span><span class="visible-xs">L</span>',
	d2: '<span class="hidden-xs">Mardi</span><span class="visible-xs">Ma</span>',
	d3: '<span class="hidden-xs">Mercredi</span><span class="visible-xs">Me</span>',
	d4: '<span class="hidden-xs">Jeudi</span><span class="visible-xs">J</span>',
	d5: '<span class="hidden-xs">Vendredi</span><span class="visible-xs">V</span>',
	d6: '<span class="hidden-xs">Samedi</span><span class="visible-xs">S</span>'
};

if(!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}