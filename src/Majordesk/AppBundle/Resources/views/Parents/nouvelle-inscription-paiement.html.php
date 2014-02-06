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
				<a class="navbar-brand" href="<?php echo $view['router']->generate('majordesk_app_index') ?>"><span class="brand">majorClass</span></a>
			</div>

			<nav class="collapse navbar-collapse navbar-responsive-collapse" role="navigation">
				<ul class="nav navbar-nav navbar-right">	
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php echo $user_mail; ?>
						<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a tabindex="-1" href="#"><i class="icon-user"></i> Profil</a></li>
							<li class="disabled"><a tabindex="-1" href="#"><i class="icon-cog"></i> Préférences</a></li>
							<li class="divider"></li>
							<li><a tabindex="-1" href="<?php echo $view['router']->generate('logout') ?>"><i class="icon-signout"></i> Déconnexion</a></li>
						</ul>
					</li>
				</ul>		
			</nav>
		</div>
	</div>
</header>

<body class="home">
	<section id="wrapper">
		<section id="home-content">
			
			<div class="container">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-lg-4">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_nouvel_abonnement', array('etape_inscription'=>1)) ?>" class="text-mid-grey">1. Inscription Eleve</a></p>
						</div>
						<div class="col-lg-4">
							<p class="text-center"><a href="<?php echo $view['router']->generate('majordesk_app_nouvel_abonnement', array('etape_inscription'=>2)) ?>" class="text-mid-grey">2. Options</a></p>
						</div>
						<div class="col-lg-4">
							<p class="text-center"><a>3. Paiement</a></p>
						</div>
						<br>
					</div>
				</div>
			</div>
			<br>
			
			<div class="container">
 <?php

	//		Affectation des paramètres obligatoires

	$parm="merchant_id=078949346700011";  //078949346700011
	$parm="$parm merchant_country=fr";
	if ($pack == 21) {
		$parm="$parm amount=59900";
	}
	else if ($pack == 23) {
		$parm="$parm amount=5990";
	}
	else if ($pack == 24) {
		$parm="$parm amount=11980";
	}
	else if ($pack == 33) {
		$parm="$parm amount=5990";
	}
	else if ($pack == 34) {
		$parm="$parm amount=11980";
	}	
	$parm="$parm currency_code=978";


	// Initialisation du chemin du fichier pathfile (à modifier)
	    //   ex :
	    //    -> Windows : $parm="$parm pathfile=c:/repertoire/pathfile";
	    //    -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
	    
	$parm="$parm pathfile=/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/payment/param/pathfile"; // Si le chemin n.est pas fourni, alors le pathfile doit etre place dans le mm dossier

	//		Si aucun transaction_id n'est affecté, request en génère
	//		un automatiquement à partir de heure/minutes/secondes
	//		Référez vous au Guide du Programmeur pour
	//		les réserves émises sur cette fonctionnalité
	//
	//		$parm="$parm transaction_id=123456";



	//		Affectation dynamique des autres paramètres
	// 		Les valeurs proposées ne sont que des exemples
	// 		Les champs et leur utilisation sont expliqués dans le Dictionnaire des données
	//
	$parm="$parm normal_return_url=http://www.majorclass.fr/inscription-response";
	$parm="$parm cancel_return_url=http://www.majorclass.fr/inscription-paiement";
	$parm="$parm automatic_response_url=http://www.majorclass.fr/inscription-autoresponse";
	//		$parm="$parm language=fr";
	//		$parm="$parm payment_means=CB,2,VISA,2,MASTERCARD,2";
	//		$parm="$parm header_flag=no";
	//		$parm="$parm capture_day=";
	//		$parm="$parm capture_mode=";
	//		$parm="$parm bgcolor=";
	//		$parm="$parm block_align=";
	//		$parm="$parm block_order=";
	//		$parm="$parm textcolor=";
	//		$parm="$parm receipt_complement=";
	
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
	
	// $pack = urlencode ($pack);
	// $username = urlencode ($view['session']->get('username'));
	// $nom = urlencode ($view['session']->get('nom'));
	// $mail = urlencode ($view['session']->get('mail'));
	// $telephone = urlencode ($view['session']->get('telephone'));
	// $password = urlencode ($view['session']->get('password'));
	// $lycee = urlencode ($view['session']->get('lycee'));
	// $username_famille = urlencode ($view['session']->get('username_famille'));
	// $nom_famille = urlencode ($view['session']->get('nom_famille'));
	// $mail_famille = urlencode ($view['session']->get('mail_famille'));
	// $telephone_famille = urlencode ($view['session']->get('telephone_famille'));
	// $password_famille = urlencode ($view['session']->get('password_famille'));
	// $adresse = urlencode ($view['session']->get('adresse'));
	// $code_postal = urlencode ($view['session']->get('code_postal'));
	// $ville = urlencode ($view['session']->get('ville'));
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
	
	// $parm="$parm caddie=".$pack.";".$username.";".$nom.";".$mail.";".$telephone.";".$password.";".$lycee.";".$view['session']->get('prog').";".$view['session']->get('gender').";".$username_famille.";".$nom_famille.";".$mail_famille.";".$telephone_famille.";".$password_famille.";".$adresse.";".$code_postal.$ville.$disponibilites;
	//		$parm="$parm customer_id=";
	// 		$parm="$parm customer_email=";
	//		$parm="$parm customer_ip_address=";
	
	// 		$parm="$parm data=";
	//		$parm="$parm return_context=";
	//		$parm="$parm target=";
	//		$parm="$parm order_id=";


	//		Les valeurs suivantes ne sont utilisables qu'en pré-production
	//		Elles nécessitent l'installation de vos fichiers sur le serveur de paiement
	//
	$parm="$parm normal_return_logo=retour.jpg";
	$parm="$parm cancel_return_logo=annulation.jpg";
	$parm="$parm submit_logo=valider.jpg";
	//      $parm="$parm logo_id=majorclass2.gif";
	//      $parm="$parm logo_id2=majorclass2.gif";
	// 		$parm="$parm advert=";
	// 		$parm="$parm background_id=";
	$parm="$parm templatefile=paiement";


	//		insertion de la commande en base de données (optionnel)
	//		A développer en fonction de votre système d'information

	// Initialisation du chemin de l'executable request (à modifier)
	// ex :
	// -> Windows : $path_bin = "c:/repertoire/bin/request";
	// -> Unix    : $path_bin = "/home/repertoire/bin/request";
	//

	$path_bin = "/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/payment/bin/request";

	//	Appel du binaire request
	// La fonction escapeshellcmd() est incompatible avec certaines options avancées
  	// comme le paiement en plusieurs fois qui nécessite  des caractères spéciaux 
  	// dans le paramètre data de la requête de paiement.
  	// Dans ce cas particulier, il est préférable d.exécuter la fonction escapeshellcmd()
  	// sur chacun des paramètres que l.on veut passer à l.exécutable sauf sur le paramètre data.
	$parm = escapeshellcmd($parm);	
	$result=exec("$path_bin $parm");

	//	sortie de la fonction : $result=!code!error!buffer!
	//	    - code=0	: la fonction génère une page html contenue dans la variable buffer
	//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error

	//On separe les differents champs et on les met dans une variable tableau
	$tableau = explode ("!", "$result");

	//	récupération des paramètres

	$code = $tableau[1];
	$error = $tableau[2];
	$message = $tableau[3];

	//  analyse du code retour		
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
		<br><div class="alert alert-info"><i class="icon-info-sign"></i> <strong>Info : </strong> Après avoir choisi votre type de carte, vous allez être redirigé vers le module de paiement en ligne Merc@net (BNP Paribas) </div><br>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="col-lg-7">Description</th>
						<th class="col-lg-2">Matière(s)</th>
						<th class="col-lg-3">Prix</th>
					</tr>
				</thead>
				<tbody>
				<?php $matieres = $view['session']->get('matieres'); if ($pack == 21) { ?>
					<tr>
						<td class="col-lg-7"><br>10 heures de cours avec un professeur Majorclass<br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>599,00 €<br><small><em>soit 299,50€ après réduction d'impôts</em></small><br><br></td>
					</tr>
				<?php } else if ($pack == 23) { ?>
					<tr>
						<td class="col-lg-7"><br><em>Pack d'essai :</em> 1 heure de cours avec un professeur Majorclass<br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>59,90 €<br><small><em>soit 29,95€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } else if ($pack == 24) { ?>
					<tr>
						<td class="col-lg-7"><br><em>Pack d'essai :</em> 2 heures de cours avec un professeur Majorclass<br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>119,80 €<br><small><em>soit 59,90€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } else if ($pack == 33) { ?>
					<tr>
						<td class="col-lg-7"><br><ul><li>1 heure de cours avec un professeur Majorclass</li><li>Découverte de la Plateforme pendant cette heure</li></ul><br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>59,90 €<br><small><em>soit 29,95€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } else if ($pack == 34) { ?>
					<tr>
						<td class="col-lg-7"><br><ul><li>2 heures de cours avec un professeur Majorclass</li><li>Découverte de la Plateforme pendant ces 2 heures</li></ul><br></td>
						<td class="col-lg-2"><br><?php  $has_maths = strpos($matieres, '1'); $has_physique = strpos($matieres, '2'); if ($has_maths!==false) { ?><span class="label label-info">Mathématiques</span><br> <?php } if ($has_physique!==false) { ?><span class="label label-success">Physique-Chimie</span><?php } ?></td>
						<td class="col-lg-3"><br>119,80 €<br><small><em>soit 59,90€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } ?>
				<?php if ($pack == 21) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>599,90 €</strong><br><br></td>
					</tr>
				<?php } else if ($pack == 23 || $pack == 33) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>59,90 €</strong><br><br></td>
					</tr>
				<?php } else if ($pack == 24 || $pack == 34) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>119,80 €</strong><br><br></td>
					</tr>
				<?php } ?>
				
				</tbody>
			</table>
		</div>
		<br>
<?php
		echo $message;
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
