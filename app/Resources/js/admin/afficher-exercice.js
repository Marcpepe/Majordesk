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
	color: '#fff',
	baseZ: 2000 //essai (vain) pour changer le z-index
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