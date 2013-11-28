$('.prof-password-input').click(function() {
	var prenom_prof = $(this).attr('data-prof-prenom');
	var id_prof = $(this).attr('data-prof-id');
	var id_matiere = $(this).attr('data-matiere-id');
	$('#prof-password-input-box').find('input[name="prof-password"]').attr('placeholder', 'Mot de passe de '+prenom_prof);
	$('#prof-password-input-box').children('input[name="prof-id"]').val(id_prof);
	$('#prof-password-input-box').children('input[name="matiere-id"]').val(id_matiere);
	$('#prof-password-input-box').removeClass('hide');
	$('input[name="prof-password"]').focus();
});
$('#prof-password-check').submit(function() {
	$('#prof-password-input-box').children('button[type="submit"]').html('<i class="icon-spinner icon-spin"></i>')
	var id_prof = $('#prof-password-input-box').children('input[name="prof-id"]').val();
	var password_prof = $('#prof-password-input-box').find('input[name="prof-password"]').val();
	var id_matiere = $('#prof-password-input-box').children('input[name="matiere-id"]').val();
	$.ajax({
		type: "POST",
		url: Routing.generate("majordesk_app_check_professeur_password", {'id_professeur' : id_prof, 'password' : password_prof, 'id_matiere' : id_matiere}),
		success: function(data){	
			$('#prof-password-input-box').children('button[type="submit"]').html('<i class="icon-ok"></i>')
			if (data === true) {
				window.location.href = Routing.generate("majordesk_app_verification_devoirs");//, {'id_matiere' : id_matiere});
			}
			else {
				$('#prof-password-input-box').prev().removeClass('hide');
			}
		},
		error: function() {
			alert('La requète n\'a pas abouti');
		}
	});
	return false;
});