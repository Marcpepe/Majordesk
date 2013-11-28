/*
 *
 * Tooltip (general javascript)
 * @info : "active la fonctionnalité des tooltips"
 *
 */
 $(function() {
	$('[rel=fix-tooltip]').tooltip({delay: { show: 400, hide: 100 }}); //container: 'body', 
	$('body').tooltip({
		selector: '[rel=tooltip]',
		// container: 'body',
		delay: { show: 400, hide: 100 }
	})
	$('body').popover({
		selector: '[rel=popover]',
		// container: 'body',
		delay: { show: 400, hide: 100 }
	})
 });