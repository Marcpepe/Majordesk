<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inscription</title>
	
	<link rel="icon" type="image/png" href="<?php echo $view['assets']->getUrl('img/home/favicon.png') ?>" />
<?php foreach ($view['assetic']->stylesheets(
		array('../app/Resources/css/common/bootstrap.min.css', '../app/Resources/css/common/majordesk.css', '../app/Resources/css/common/font-awesome.min.css'),
		array('yui_css'),
		array('output' => 'css/*')
	) as $url): ?>
		<link rel="stylesheet" href="<?php echo $view->escape($url) ?>" />
<?php endforeach; ?>
</head>

<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
	<div class="container">
		<div class="col-xs-12 col-lg-12">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				  <span class="sr-only">Activer la navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo $view['router']->generate('majordesk_app_principe_index') ?>"><span class="brand">majorClass</span></a>
			</div>

			<nav class="collapse navbar-collapse navbar-responsive-collapse" role="navigation">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo $view['router']->generate('majordesk_app_principe_index') ?>">Principe</a></li>
				    <li><a href="<?php echo $view['router']->generate('majordesk_app_presentation_plateforme') ?>">La plateforme</a></li>
				    <li><a href="<?php echo $view['router']->generate('majordesk_app_presentation_tarifs') ?>">Tarifs</a></li>
				    <li><a href="<?php echo $view['router']->generate('majordesk_app_presentation_equipe') ?>">Qui sommes-nous?</a></li>
				    <li><a href="<?php echo $view['router']->generate('majordesk_app_index') ?>">Se connecter <i class="icon-off"></i></a></li>
				</ul>		
			</nav>
		</div>
	</div>
</header>

<body class="home">
	<section id="wrapper">
		<section id="home-content">
			<div id="home-banner-purple">
				<br><br>
				<div class="container home-container">
					<div class="text-center" style="font-size:35px">
						<br>
					</div>
				</div>
				<br>
			</div>
			<div class="container home-container">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-lg-3">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_inscription', array('etape_inscription'=>1)) ?>" class="text-mid-grey">1. Inscription Eleve</a></p>
						</div>
						<div class="col-lg-3">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_inscription', array('etape_inscription'=>2)) ?>" class="text-mid-grey">2. Inscription Parent(s)</a></p>
						</div>
						<div class="col-lg-3">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_inscription', array('etape_inscription'=>3)) ?>" class="text-mid-grey">3. Options</a></p>
						</div>
						<div class="col-lg-3">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_inscription_paiement') ?>">4. Paiement</a></p>
						</div>
						<br>
					</div>
				</div>
			</div>
			<br>
			
			<div class="container home-container">
 <?php
	
	// Affectation des paramètres obligatoires
	// Allocation of mandatory parameters

	$parm="merchant_id=078949346700011";
	$parm="$parm merchant_country=fr";


    // Initialisation du chemin du fichier pathfile (à modifier)
    //   ex :
    //    -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
    //    -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
    //
    // Cette variable est facultative. Si elle n'est pas renseignée,
    // l'API positionne la valeur à "./pathfile".
    //
    // Path initialisation of the file pathfile (to modify)
    //   ex :
    //    -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
    //    -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
    //
    // This variable is optional. If not set, the API sets the value with "./pathfile"

	$parm="$parm pathfile=/home/majorcla/mercanet/subscription/param/pathfile";

	//	Si aucun transaction_id n'est affecté, recordabo en génère
	//	un automatiquement à partir de heure/minutes/secondes
	//	Référez vous au Guide du Programmeur pour
	//	les réserves émises sur cette fonctionnalité
	//
	//	if no transaction_id is set, recordabo automatically generates one thanks
	//	to hour/minute/second. Refer to the Programmer guide for the  restrictions
	//	of this function
	//
	//	$parm="$parm transaction_id=123456";



	//	Affectation dynamique des autres paramètres
	// 	Les valeurs proposées ne sont que des exemples
	// 	Les champs et leur utilisation sont expliqués dans le Dictionnaire des données
	//
	//	Dynamic allocation of the others parameters
	//	The values suggested are mere examples
	//	The fields and the way to use them is described in the Data dictionary
	//
	$parm="$parm sub_normal_return_url=http://www.majorclass.fr/inscription-response-sub";
	$parm="$parm sub_cancel_return_url=http://www.majorclass.fr/login";
	$parm="$parm sub_automatic_response_url=http://www.majorclass.fr/inscription-autoresponse-sub";
	// 	$parm="$parm currency_code=978";
	// 	$parm="$parm language=fr";
	// 	$parm="$parm merchant_language=";
	//	$parm="$parm card_list=CB,VISA,MASTERCARD";
	$parm="$parm header_flag=no";
	//	$parm="$parm capture_day=";
	//	$parm="$parm capture_mode=";
	//	$parm="$parm bgcolor=";
	//	$parm="$parm textcolor=";
	//	$parm="$parm receipt_complement=";
	
	// $pack = urlencode ($pack);
	// $username = urlencode ($view['session']->get('username'));
	// $nom = urlencode ($view['session']->get('nom'));
	// $mail = urlencode ($view['session']->get('mail'));
	// if ($view['session']->get('telephone') != '') {
		// $telephone = urlencode ($view['session']->get('telephone'));
	// }
	// else {
		// $telephone = urlencode ('%%');
	// }
	// $password = urlencode ($view['session']->get('password'));
	// if ($view['session']->get('lycee') != '') {
		// $lycee = urlencode ($view['session']->get('lycee'));
	// }
	// else {
		// $lycee = urlencode ('%%');
	// }
	// $prog = urlencode ($view['session']->get('prog'));
	// $gender = urlencode ($view['session']->get('gender'));
	// $username_famille = urlencode ($view['session']->get('username_famille'));
	// $nom_famille = urlencode ($view['session']->get('nom_famille'));
	// $mail_famille = urlencode ($view['session']->get('mail_famille'));
	// if ($view['session']->get('telephone_famille') != '') {
		// $telephone_famille = urlencode ($view['session']->get('telephone_famille'));
	// }
	// else {
		// $telephone_famille = urlencode ('%%');
	// }
	// $password_famille = urlencode ($view['session']->get('password_famille'));
	// if ($view['session']->get('adresse') != '') {
		// $adresse = urlencode ($view['session']->get('adresse'));
	// }
	// else {
		// $adresse = urlencode ('%%');
	// }
	// if ($view['session']->get('code_postal') != '') {
		// $code_postal = urlencode ($view['session']->get('code_postal'));
	// }
	// else {
		// $code_postal = urlencode ('%%');
	// }
	// if ($view['session']->get('ville') != '') {
		// $ville = urlencode ($view['session']->get('ville'));
	// }
	// else {
		// $ville = urlencode ('%%');
	// }
	// $disponibilites = '';
	// for($i=1;$i<=7;$i++) {
		// ${'jour_'.$i} = $view['session']->get('jour_'.$i);	
		// ${'heure_'.$i} = $view['session']->get('heure_'.$i);	
		// if (!empty(${'jour_'.$i}) && !empty(${'heure_'.$i})) {
			// $disponibilites .= ";".${'jour_'.$i}."##".${'heure_'.$i};
		// }
		// else {
			// break;
		// }
	// }
	// $parm="$parm caddie=".$pack."&&".$username."&&".$nom."&&".$mail."&&".$telephone."&&".$password."&&".$lycee."&&".$prog."&&".$gender."&&".$username_famille."&&".$nom_famille."&&".$mail_famille."&&".$telephone_famille."&&".$password_famille."&&".$adresse."&&".$code_postal."&&".$ville.$disponibilites;	
	
	$caddie = array();
	$caddie[0] = $pack;
	$caddie[1] = $view['session']->get('username');
	$caddie[2] = $view['session']->get('nom');
	$caddie[3] = $view['session']->get('mail');
	$caddie[4] = $view['session']->get('telephone');
	$caddie[5] = $view['session']->get('password');
	$caddie[6] = $view['session']->get('lycee');
	$caddie[7] = $view['session']->get('prog');
	$caddie[8] = $view['session']->get('gender');
	$caddie[9] = $view['session']->get('username_famille');
	$caddie[10] = $view['session']->get('nom_famille');
	$caddie[11] = $view['session']->get('mail_famille');
	$caddie[12] = $view['session']->get('telephone_famille');
	$caddie[13] = $view['session']->get('password_famille');
	$caddie[14] = $view['session']->get('adresse');
	$caddie[15] = $view['session']->get('code_postal');
	$caddie[16] = $view['session']->get('ville');
	$caddie[17] = $view['session']->get('matieres');
	for($i=1;$i<=7;$i++) {
		${'jour_'.$i} = $view['session']->get('jour_'.$i);	
		${'heure_'.$i} = $view['session']->get('heure_'.$i);	
		if (!empty(${'jour_'.$i}) && !empty(${'heure_'.$i})) {
			$caddie[17+$i] = ${'jour_'.$i}."##".${'heure_'.$i};
		}
		else {
			break;
		}
	}
	
	$xcaddie = base64_encode(serialize($caddie));
	$parm="$parm caddie=".$xcaddie;
	
	// $parm="$parm data=NO_SUB_DATA;NO_PAY_MSG";
	
	//	$parm="$parm return_context=";
	//	$parm="$parm target=";
	//	$parm="$parm order_id=";
	//	$parm="$parm customer_ip_address=";


	//	$parm="$parm sub_address1=";
	//	$parm="$parm sub_address2=";
	if ($pack == 11) {
		$parm="$parm sub_amount=5990";
		// $parm="$parm sub_amount=100";
	} else if ($pack == 22) {
		$parm="$parm sub_amount=0";
		// $parm="$parm sub_amount=0";
	} else if ($pack == 31) {
		$parm="$parm sub_amount=65890";
		// $parm="$parm sub_amount=100";
	} else if ($pack == 32) {
		$parm="$parm sub_amount=5990";
		// $parm="$parm sub_amount=100";
	}
	//	$parm="$parm sub_city=";
	//	$parm="$parm sub_civil_status=";
	//	$parm="$parm sub_country=";
	//	$parm="$parm sub_description=";
	//	$parm="$parm sub_email=";
	//	$parm="$parm sub_firstname=";
	//	$parm="$parm sub_lastname=";
	//	$parm="$parm sub_operation_code=";
	//	$parm="$parm sub_subscriber_id=";
	if ($pack == 11 || $pack == 31 || $pack == 32) {
		$parm="$parm sub_type=1";
	}
	else {
		$parm="$parm sub_type=0";
	}
	//	$parm="$parm sub_telephone=";
	//	$parm="$parm sub_zipcode=";

	//	Les variables suivantes ne sont utilisables qu'en pré-production
	//	Elles nécessitent l'installation de vos fichiers sur le serveur d'abonnement
	//
	//	The following variables are only used in pre-production
	//	They require your files to be installed of on the subscription server
	//
	$parm="$parm normal_return_logo=retour.jpg";
	$parm="$parm cancel_return_logo=annulation.jpg";
	$parm="$parm submit_logo=valider.jpg";
	// 	$parm="$parm logo_id=";
	//  $parm="$parm logo_id2=majorclass2.gif";
	// 	$parm="$parm advert=";
	// 	$parm="$parm background_id=";
	$parm="$parm templatefile=subscription";


	//	insertion du nouvel abonnement en base de données (optionnel)
	//	A développer en fonction de votre système d'information

	//	Data base subscription insertion (optional)
	//	This development depends on your information system

	// Initialisation du chemin de l'executable recordabo (à modifier)
	// ex :
	// -> Windows : $path_bin = "c:\\repertoire\\bin\\recordabo";
	// -> Unix    : $path_bin = "/home/repertoire/bin/recordabo";
	//
	// Initialization of the recordabo executable path (to modify)
	// ex :
	// -> Windows : $path_bin = "c:\\repertoire\\bin\\responseabo";
	// -> Unix    : $path_bin = "/home/repertoire/bin/responseabo";
	//

	// ex :
	// -> Windows : $path_bin = "c:\\repertoire\\bin\\recordabo";
	// -> Unix    : $path_bin = "/home/repertoire/bin/recordabo";
	//

	$path_bin="/home/majorcla/mercanet/subscription/bin/recordabo";

	//	Appel du binaire recordabo
	//	Recordabo binary call

	$result=exec("$path_bin $parm");

	//	sortie de la fonction : !code!error!buffer!
	//	    - code=0	: la fonction génère une page HTML contenue dans la variable buffer
	//	    - code=-1 : La fonction retourne un message d'erreur dans la variable error
	//
	//	Output of the function: !code!error!buffer!
	//	    - code=0	: the function creates an HTML page contained in the buffer variable
	//	    - code=-1 : the function returns an error message in the error variable
	//

	//On separe les differents champs et on les met dans une variable tableau
	//the different fields are separated and put in a table

	$tableau = explode ("!", "$result");

	// récupération des paramètres
	// parameters recovery

	$code = $tableau[1];
	$error = $tableau[2];
	$message = $tableau[3];

	//  analyse du code retour
	//  return code analysis

  if (( $code == "" ) && ( $error == "" ) )
 	{
		echo '<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <strong>Erreur : </strong> Une erreur est survenue lors du chargement du script de paiement. Veuillez réessayer ultérieurement ou contacter le webmaster.<br><br><em>exécutable non trouvé : '.$path_bin.'</em></div>';
 	}

	// Erreur, affiche le message d'erreur
	// Error, display of the error message 

	else if ($code != 0){
		echo '<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <strong>Erreur : </strong> Une erreur est survenue lors de l\'exécution du script de paiement. Veuillez réessayer ultérieurement ou contacter le webmaster.<br><br><em>erreur : '.$error.'</em></div>';
	}

	// OK, affichage du bouton d'inscription
	// OK, display of the registration button
	else {
?>
		<br>
		<div class="alert alert-info">
			<i class="icon-info-sign"></i> <strong>Info : </strong> En cliquant sur Valider, vous allez être redirigé vers le module de paiement en ligne Merc@net (BNP Paribas)
		</div><br>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="col-lg-7">Description</th>
						<th class="col-lg-2">Matière(s)</th>
						<th class="col-lg-3">Prix</th>
					</tr>
				</thead>
				<?php $matieres = $view['session']->get('matieres'); if ($pack == 22) { ?>
					<tr>
						<td class="col-lg-7"><br>Enregistrement de votre carte bancaire pour un débit cours par cours<br><br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>0,00 €<br><br></td>
					</tr>
				<?php } else { ?>
					<tr>
						<td class="col-lg-7"><br>1 mois d'abonnement à la Plateforme Majorclass<br><small><em>Prochain débit le <?php echo date('d/m/Y', strtotime("+1 month")); ?>, sauf si vous résiliez votre abonnement avant cette date</em></small><br><br></td>
						<td class="col-lg-2"><br><h4><span class="label label-info">Mathématiques</span></h4></td>
						<td class="col-lg-3"><br>59,90 €<br></td>
					</tr>
				<?php } ?>
				<?php if ($pack == 31) { ?>
					<tr>
						<td class="col-lg-7"><br>10 heures de cours avec un Professeur de l'équipe Majorclass<br><br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>599,00 €<br><small><em>soit 299,50€ après réduction d'impôts</em></small><br><br></td>
					</tr>
				<?php } ?>
				<?php if ($pack == 22) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>0,00 €</strong><br><br><span class="pull-right"><?php echo $message; ?></span></td>
					</tr>
				<?php } else if ($pack == 31) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>658,90 €</strong><br><br><span class="pull-right"><?php echo $message; ?></span></td>
					</tr>
				<?php } else if ($pack == 11 || $pack == 32) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>59,90 €</strong><br><br><span class="pull-right"><?php echo $message; ?></span></td>
					</tr>
				<?php } ?>
				<tbody>
				</tbody>
			</table>
		</div>
		
<?php
	}
?>

			</div>
		</section>

		<footer>
			<div class="row well">
				<div class="container home-container">
					<section class="col-lg-2 pull-left">
					  <h4 class="text-mid-grey">Majorclass</h4>
					  <ul class="list-unstyled">
						<li>
						  <a href="<?php echo $view['router']->generate('majordesk_app_principe_index') ?>">Le principe</a>
						</li>
						<li>
						  <a href="<?php echo $view['router']->generate('majordesk_app_presentation_plateforme') ?>">La plateforme</a>
						</li>
						<li>
						  <a href="<?php echo $view['router']->generate('majordesk_app_presentation_tarifs') ?>">Les tarifs</a>
						</li>
						<li>
						  <a href="<?php echo $view['router']->generate('majordesk_app_presentation_equipe') ?>">L'équipe</a>
						</li>
					  </ul>
					</section>
					<section class="col-lg-2 pull-left">
					  <h4 class="text-mid-grey">Aide</h4>
					  <ul class="list-unstyled">
						<li>
						  <a href="#">Foire aux Questions</a>
						</li>
						<li>
						  <a href="#">Conditions d'utilisation</a>
						</li>
						<li>
						  <a href="#">Vie privée</a>
						</li>
					  </ul>
					</section>
					<section class="col-lg-2 pull-left">
					  <h4 class="text-mid-grey">Divers</h4>
					  <ul class="list-unstyled">
						<li>
						  <a href="#">Partenaires</a>
						</li>
						<li>
						  <a href="#">Presse</a>
						</li>
						<li>
						  <a href="#">Recrutement</a>
						</li>
					  </ul>
					</section>
					<section class="col-lg-2 pull-left">
					  <h4 class="text-mid-grey">Soutenez-nous</h4>
					  <ul class="list-unstyled">
						<li>
						  <a href="#">Like (F)</a>
						</li>
						<li>
						  <a href="#">Follow (T)</a>
						</li>
					  </ul>
					</section>
				</div>
			</div>
			
		</footer>
	</section>
	
</body>
</html>
