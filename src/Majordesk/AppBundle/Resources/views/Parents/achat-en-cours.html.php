<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Achat d'heures en cours - Majorclass</title>
	
	<link rel="icon" type="image/png" href="<?php echo $view['assets']->getUrl('img/home/favicon.png') ?>" />
	<link rel="stylesheet" href="/css/46c62fb.css" /> <!--A CHANGER: CETTE PAGE DOIT ETRE GENERER EN TWIG -->
{#<?php foreach ($view['assetic']->stylesheets(
		array('../app/Resources/css/common/bootstrap.min.css', '../app/Resources/css/common/majordesk.css', '../app/Resources/css/common/font-awesome.min.css'),
		array('yui_css'),
		array('output' => 'css/*')
	) as $url): ?>
		<link rel="stylesheet" href="<?php echo $view->escape($url) ?>" />
<?php endforeach; ?>#}
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
				<a class="navbar-brand" style="padding:0" href="<?php echo $view['router']->generate('majordesk_app_index') ?>"><img src="/img/home/bar-logo.png" /><!--A CHANGER: CETTE PAGE DOIT ETRE GENERER EN TWIG --></a>
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
 <?php

	//		Affectation des paramètres obligatoires

	$parm="merchant_id=078949346700011";  //082584341411112
	$parm="$parm merchant_country=fr";
	if ($pack == 1) {
		$parm="$parm amount=59900";
		// $parm="$parm amount=100";
	}
	else if ($pack == 2) {
		$parm="$parm amount=179700";
		// $parm="$parm amount=100";
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
	$parm="$parm normal_return_url=http://www.majorclass.fr/achat-response";
	$parm="$parm cancel_return_url=http://www.majorclass.fr/parents/achat-en-cours-".$pack;
	$parm="$parm automatic_response_url=http://www.majorclass.fr/achat-autoresponse";
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
	$caddie[1] = $famille->getId();

	
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
						<th class="col-lg-2">Matière</th>
						<th class="col-lg-3">Prix</th>
					</tr>
				</thead>
				<?php if ($pack == 1) { ?>
					<tr>
						<td class="col-lg-7"><br>10 heures de cours avec un professeur Majorclass<br></td>
						<td class="col-lg-2"><br><em>toutes matières</em></td>
						<td class="col-lg-3"><br>599,00 €<br><small><em>soit 299,50€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } else if ($pack == 2) { ?>
					<tr>
						<td class="col-lg-7"><br>30 heures de cours avec un professeur Majorclass<br></td>
						<td class="col-lg-2"><br><em>toutes matières</em></td>
						<td class="col-lg-3"><br>1797,00 €<br><small><em>soit 898,50€ après réduction d'impôts</em></small><br></td>
					</tr>
				<?php } ?>
				<?php if ($pack == 1) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>599,00 €</strong><br><br></td>
					</tr>
				<?php } else if ($pack == 2) { ?>
					<tr>
						<td class="col-lg-7"></td>
						<td class="col-lg-2"><span class="pull-right"><br>Total : </span></td>
						<td class="col-lg-3"><br><strong>1797,00 €</strong><br><br></td>
					</tr>
				<?php } ?>
				<tbody>
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

		
	</section>
	
</body>
</html>
