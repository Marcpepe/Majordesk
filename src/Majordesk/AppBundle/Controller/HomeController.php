<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Symfony\Component\HttpFoundation\Response;

use Majordesk\AppBundle\Form\Type\InscriptionEleveType;
use Majordesk\AppBundle\Form\Type\InscriptionFamilleType;

use Majordesk\AppBundle\Entity\Eleve;
use Majordesk\AppBundle\Entity\EleveMatiere;
use Majordesk\AppBundle\Entity\Client;
use Majordesk\AppBundle\Entity\Famille;
use Majordesk\AppBundle\Entity\Disponibilite;
use Majordesk\AppBundle\Entity\Paiement;

class HomeController extends Controller
{
    public function connexionAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_professeur'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_parents'));
		}
		elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('majordesk_app_index_admin'));
		}      
		else {
			return $this->redirect($this->generateUrl('login'));
		}
    }
	
	public function principeIndexAction()
    {
		return $this->render('MajordeskAppBundle:Home:index.html.twig');
    }
	
	public function phpInfoAction()
    {
		return $this->render('MajordeskAppBundle:Home:phpinfo.html.php');
    }
	
	public function coursPresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-cours.html.twig');
    }
	
	public function plateformePresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-plateforme.html.twig');
    }
	
	public function tarifsPresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-tarifs.html.twig');
    }
	
	public function equipePresentationAction()
    {
		return $this->render('MajordeskAppBundle:Home:presentation-equipe.html.twig');
    }
	
	public function inscriptionAction($etape_inscription)
    {
		$session = $this->get('session');
		$em = $this->getDoctrine()->getManager();
		$etape_session = $session->get('etape_inscription');
		if ($etape_inscription == 1) {
			if ($etape_session == null) {
				$session->set('etape_inscription', 1);
			}
			
			$eleve = new Eleve();
			$username = $session->get('username');
			if ($username != '') {
					$eleve->setUsername($username);
				$nom = $session->get('nom');
					$eleve->setNom($nom);
				$prog = $session->get('prog');
				$prog = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Programme')
							 ->find($prog);
					$eleve->setProgramme($prog);
				$lycee = $session->get('lycee');
					$eleve->setLycee($lycee);
				$telephone = $session->get('telephone');	
					$eleve->setTelephone($telephone);
				$email = $session->get('mail');	
					$eleve->setMail($email);
				
				for($i=1;$i<=7;$i++) {
					$disponibilite = new Disponibilite();
					${'jour_'.$i} = $session->get('jour_'.$i);	
					${'heure_'.$i} = $session->get('heure_'.$i);	
					if (!empty(${'jour_'.$i}) && !empty(${'heure_'.$i})) {
						$disponibilite->setJour(${'jour_'.$i});
						$disponibilite->setHeureDebut(${'heure_'.$i});
						$eleve->addDisponibilite($disponibilite);
					}
					else {
						break;
					}
				}
			}
		
			$form = $this->createForm(new InscriptionEleveType(), $eleve);
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{
					if ($form->getData()->getProgramme() != null) {
						$session->set('prog', $form->getData()->getProgramme()->getId());
						$session->set('username', ucfirst($form->getData()->getUsername()));
						$session->set('nom', ucfirst($form->getData()->getNom()));
						$session->set('lycee', $form->getData()->getLycee());
						$session->set('telephone', $form->getData()->getTelephone());
						$session->set('mail', $form->getData()->getMail());
						$session->set('password', $form->getData()->getPassword());
						$i=1;
						if ($form->getData()->getDisponibilites() != null) {
							foreach($form->getData()->getDisponibilites() as $disponibilite) {
								$session->set('jour_'.$i, $disponibilite->getJour());
								$session->set('heure_'.$i, $disponibilite->getHeureDebut());
								$i++;
							}
						}
							
						$session->set('etape_inscription', 2);
						return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 2)));
					}
					else {
						$this->get('session')->getFlashBag()->add('info', 'Programme non renseigné.');
					}
				}
				$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
			}
		}
		else if ($etape_inscription == 2) {
			if ($etape_session >= 2) {
				$famille = new Famille();
				$cli = new Client();
				$username_famille = $session->get('username_famille');
				if ($username_famille != '') {
						$cli->setUsername($username_famille);
					$gender = $session->get('gender');
						$cli->setGender($gender);
					$nom_famille = $session->get('nom_famille');
						$cli->setNom($nom_famille);
					$telephone_famille = $session->get('telephone_famille');	
						$cli->setTelephone($telephone_famille);
					$adresse = $session->get('adresse');	
						$cli->setAdresse($adresse);
					$code_postal = $session->get('code_postal');	
						$cli->setCodePostal($code_postal);
					$ville = $session->get('ville');	
						$cli->setVille($ville);
					$mail_famille = $session->get('mail_famille');	
						$cli->setMail($mail_famille);
				}
				$famille->addClient($cli);
			
				$form = $this->createForm(new InscriptionFamilleType(), $famille);
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{	
						foreach ($form->getData()->getClients() as $client) {
							$session->set('gender', ucfirst($client->getGender()));
							$session->set('username_famille', ucfirst($client->getUsername()));
							$session->set('nom_famille', ucfirst($client->getNom()));
							$session->set('telephone_famille', $client->getTelephone());
							$session->set('mail_famille', $client->getMail());
							$session->set('adresse', $client->getAdresse());
							$session->set('code_postal', $client->getCodePostal());
							$session->set('ville', ucfirst($client->getVille()));
							$session->set('password_famille', $client->getPassword());
						}

						$session->set('etape_inscription', 3);
						return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
					}
					$this->get('session')->getFlashBag()->add('warning-parents', 'Un ou plusieurs champs ont été mal remplis.');
				}
			}
			else {
				return $this->redirect($this->generateUrl('majordesk_app_profil_famille', array('etape_inscription' => 1)));
			}
		}
		else if ($etape_inscription == 3) {
		
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$matiere_maths = $request->request->get('matiere_maths');
				$matiere_physique = $request->request->get('matiere_physique');

				if (!empty($matiere_maths) || !empty($matiere_physique)) {
				
					$pack = $request->request->get('pack');
					
					if (!empty($pack)) {
						$matieres = '';
						if (!empty($matiere_maths)) {
							$matieres = '1';
						}
						if (!empty($matiere_physique)) {
							if ($matieres == '') {
								$matieres = '2';
							} else {
								$matieres .= '##2';
							}
						}
						$session->set('matieres', $matieres);
						$session->set('pack', $pack);
						return $this->redirect($this->generateUrl('majordesk_app_inscription_paiement'));
					}
					$this->get('session')->getFlashBag()->add('warning-formule', 'Veuillez sélectionner un pack.');
					return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
				}
				$this->get('session')->getFlashBag()->add('warning-matiere', 'Veuillez sélectionner au moins une matière.');
				return $this->redirect($this->generateUrl('majordesk_app_inscription', array('etape_inscription' => 3)));
			}
		
			return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
				'etape_inscription' => $etape_inscription,
			));
		}
		else if ($etape_inscription == 4) {
			$pack = $this->get('request')->request->get('pack');
			$packs = array(11,21,22,23,24,31,32,33,34);
			if (in_array($pack, $packs)) {
				return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
					'etape_inscription' => $etape_inscription,
					'pack' => $pack,
				));
			}
			else {
				$this->get('session')->getFlashBag()->add('warning-pack', 'Une erreur est survenue lors de la sélection du pack.');
				return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
					'etape_inscription' => 3,
				));		
			}
		}
		
        return $this->render('MajordeskAppBundle:Home:inscription.html.twig', array(
			'etape_inscription' => $etape_inscription,
			'form' => $form->createView()
		));
	}
	
	public function cgvMajorclassAction()
    {
		$file_path = '/home/majorcla/cgv/cgv_majorclass.pdf';
		
		return new Response(file_get_contents($file_path), 200, array(
			'Content-Type' => 'application/pdf'
		));
    }
	
	public function cgvMajordeskAction()
    {
		$file_path = '/home/majorcla/cgv/cgv_majordesk.pdf';
		
		return new Response(file_get_contents($file_path), 200, array(
			'Content-Type' => 'application/pdf'
		));
    }
	
	public function inscriptionPaiementAction()
    {
		$session = $this->get('session');
		
		if ($session->get('etape_inscription') == 3) {
			$session->set('etape_inscription', 4);
		}
		
		$pack = $this->get('request')->request->get('pack');
	
		if ($pack == null){
			$pack = $session->get('pack');
		}
		else {
			$session->set('pack', $pack);
		}
		
		$packs_paiement = array(21,23,24,33,34);
		$packs_subscription = array(11,22,31,32);
		
		if (in_array($pack, $packs_subscription)) {
			return $this->render('MajordeskAppBundle:Home:inscription-subscription.html.php', array(
				'pack' => $pack,
			));
		}
		else if (in_array($pack, $packs_paiement)) {
			return $this->render('MajordeskAppBundle:Home:inscription-paiement.html.php', array(
				'pack' => $pack,
			));
		}
		else {
			$this->get('session')->getFlashBag()->add('warning-pack', 'Une erreur est survenue lors de la sélection du pack. Veuillez recommencer l\'inscription en cliquant sur "Se connecter" en haut à droite.');
			return $this->redirect($this->generateUrl('majordesk_app_inscription', array(
				'etape_inscription' => 3,
			)));
		}
	}
	
	/**
	  * MERCANET CLASSIQUE
	  */
	public function inscriptionAutoresponseAction()
    {
		$request = $this->get('request');
		$session = $this->get('session');
		
		// Récupération de la variable cryptée DATA
		$DATA = $request->request->get('DATA');
		$message="message=".$DATA;

		// Initialisation du chemin du fichier pathfile (à modifier)
			//   ex :
			//    -> Windows : $pathfile="pathfile=c:/repertoire/pathfile"
			//    -> Unix    : $pathfile="pathfile=/home/repertoire/pathfile"
			
		$pathfile="pathfile=/home/majorcla/mercanet/payment/param/pathfile";

		//Initialisation du chemin de l'executable response (à modifier)
		//ex :
		//-> Windows : $path_bin = "c:/repertoire/bin/response"
		//-> Unix    : $path_bin = "/home/repertoire/bin/response"
		//

		$path_bin = "/home/majorcla/mercanet/payment/bin/response";

		// Appel du binaire response
		$message = escapeshellcmd($message);
		$result=exec("$path_bin $pathfile $message");

		//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
		//		- code=0	: la fonction retourne les données de la transaction dans les variables v1, v2, ...
		//				: Ces variables sont décrites dans le GUIDE DU PROGRAMMEUR
		//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error


		//	on separe les differents champs et on les met dans une variable tableau

		$tableau = explode ("!", $result);

		$code = $tableau[1];
		$error = $tableau[2];
		$merchant_id = $tableau[3];
		$merchant_country = $tableau[4];
		$amount = $tableau[5];
		$transaction_id = $tableau[6];
		$payment_means = $tableau[7];
		$transmission_date= $tableau[8];
		$payment_time = $tableau[9];
		$payment_date = $tableau[10];
		$response_code = $tableau[11];
		$payment_certificate = $tableau[12];
		$authorisation_id = $tableau[13];
		$currency_code = $tableau[14];
		$card_number = $tableau[15];
		$cvv_flag = $tableau[16];
		$cvv_response_code = $tableau[17];
		$bank_response_code = $tableau[18];
		$complementary_code = $tableau[19];
		$complementary_info= $tableau[20];
		$return_context = $tableau[21];
		$caddie = $tableau[22];
		$receipt_complement = $tableau[23];
		$merchant_language = $tableau[24];
		$language = $tableau[25];
		$customer_id = $tableau[26];
		$order_id = $tableau[27];
		$customer_email = $tableau[28];
		$customer_ip_address = $tableau[29];
		$capture_day = $tableau[30];
		$capture_mode = $tableau[31];
		$data = $tableau[32];
		$order_validity = $tableau[33];
		$transaction_condition = $tableau[34];
		$statement_reference = $tableau[35];
		$card_validity = $tableau[36];
		$score_value = $tableau[37];
		$score_color = $tableau[38];
		$score_info = $tableau[39];
		$score_threshold = $tableau[40];
		$score_profile = $tableau[41];

		$log_name = date('Y-m-d_H-i-s');
		
		if ($response_code == '00') {
			$logfile="/home/majorcla/mercanet/payment/autologs/".$log_name."_success.txt";
		}
		else {
			$logfile="/home/majorcla/mercanet/payment/autologs/".$log_name."_fail.txt";
		}

		// Ouverture du fichier de log en append

		$fp=fopen($logfile, "a");

		//  analyse du code retour

	  if (( $code == "" ) && ( $error == "" ) )
		{
			fwrite( $fp, "erreur appel response\n");
			fwrite( $fp, "executable response non trouve $path_bin\n");
		}

		//	Erreur, sauvegarde le message d'erreur

		else if ( $code != 0 ){
			fwrite( $fp, " API call error.\n");
			fwrite( $fp, "Error message :  $error\n");
		}
		else {
			fwrite( $fp, "----------------TRANSACTION----------------\n");
			fwrite( $fp, "merchant_id : $merchant_id\n");
			fwrite( $fp, "merchant_country : $merchant_country\n");
			fwrite( $fp, "amount : $amount\n");
			fwrite( $fp, "transaction_id : $transaction_id\n");
			fwrite( $fp, "transmission_date: $transmission_date\n");
			fwrite( $fp, "payment_means: $payment_means\n");
			fwrite( $fp, "payment_time : $payment_time\n");
			fwrite( $fp, "payment_date : $payment_date\n");
			fwrite( $fp, "response_code : $response_code\n");
			fwrite( $fp, "payment_certificate : $payment_certificate\n");
			fwrite( $fp, "authorisation_id : $authorisation_id\n");
			fwrite( $fp, "currency_code : $currency_code\n");
			fwrite( $fp, "card_number : $card_number\n");
			fwrite( $fp, "cvv_flag: $cvv_flag\n");
			fwrite( $fp, "cvv_response_code: $cvv_response_code\n");
			fwrite( $fp, "bank_response_code: $bank_response_code\n");
			fwrite( $fp, "complementary_code: $complementary_code\n");
			fwrite( $fp, "complementary_info: $complementary_info\n");
			fwrite( $fp, "return_context: $return_context\n");
			fwrite( $fp, "caddie : $caddie\n");
			fwrite( $fp, "receipt_complement: $receipt_complement\n");
			fwrite( $fp, "merchant_language: $merchant_language\n");
			fwrite( $fp, "language: $language\n");
			fwrite( $fp, "customer_id: $customer_id\n");
			fwrite( $fp, "order_id: $order_id\n");
			fwrite( $fp, "customer_email: $customer_email\n");
			fwrite( $fp, "customer_ip_address: $customer_ip_address\n");
			fwrite( $fp, "capture_day: $capture_day\n");
			fwrite( $fp, "capture_mode: $capture_mode\n");
			fwrite( $fp, "data: $data\n");	
			fwrite( $fp, "order_validity: $order_validity\n");
			fwrite( $fp, "transaction_condition: $transaction_condition\n");
			fwrite( $fp, "statement_reference: $statement_reference\n");
			fwrite( $fp, "card_validity: $card_validity\n");
			fwrite( $fp, "card_validity: $score_value\n");
			fwrite( $fp, "card_validity: $score_color\n");
			fwrite( $fp, "card_validity: $score_info\n");
			fwrite( $fp, "card_validity: $score_threshold\n");
			fwrite( $fp, "card_validity: $score_profile\n\n");
			
			fwrite( $fp, "----------------INSCRIPTION----------------\n");
			$infos = unserialize(base64_decode($caddie));
			fwrite( $fp, "pack: ".$infos[0]."\n");
			fwrite( $fp, "prénom enfant: ".$infos[1]."\n");
			fwrite( $fp, "nom enfant: ".$infos[2]."\n");
			fwrite( $fp, "mail enfant: ".$infos[3]."\n");
			fwrite( $fp, "téléphone enfant: ".$infos[4]."\n");
			fwrite( $fp, "password enfant: ".$infos[5]."\n");
			fwrite( $fp, "lycée: ".$infos[6]."\n");
			fwrite( $fp, "programme: ".$infos[7]."\n");
			fwrite( $fp, "parenté: ".$infos[8]."\n");
			fwrite( $fp, "prénom parent: ".$infos[9]."\n");
			fwrite( $fp, "nom parent: ".$infos[10]."\n");
			fwrite( $fp, "mail parent: ".$infos[11]."\n");
			fwrite( $fp, "téléphone parent: ".$infos[12]."\n");
			fwrite( $fp, "password parent: ".$infos[13]."\n");
			fwrite( $fp, "adresse: ".$infos[14]."\n");
			fwrite( $fp, "code postal: ".$infos[15]."\n");
			fwrite( $fp, "ville: ".$infos[16]."\n");
			fwrite( $fp, "matieres: ".$infos[17]."\n");
			for($i=1;$i<=7;$i++) {
				if (!empty($infos[17+$i])) {
					fwrite( $fp, "dispo ".$i.": ".$infos[17+$i]."\n\n");
				}
				else {
					break;
				}
			}
			
			if ($response_code == '00') {	
			
				$em = $this->getDoctrine()->getManager();
				$factory = $this->get('security.encoder_factory');
				
				/* CREATION ELEVE */
				$eleve = new Eleve();
				
				$username =              $infos[1];
				$nom =                   $infos[2];
				$mail =                  $infos[3];
				$telephone =             $infos[4];
				$password =              $infos[5];
				$lycee =                 $infos[6];
				$programme =             $infos[7];
				$matieres =              explode("##", $infos[17]);
				$jour_1='';
				$jour_2='';
				$jour_3='';
				$jour_4='';
				$jour_5='';
				$jour_6='';
				$jour_7='';
				$heure_1='';
				$heure_2='';
				$heure_3='';
				$heure_4='';
				$heure_5='';
				$heure_6='';
				$heure_7='';
				for($i=1;$i<=7;$i++) {
					if (!empty($infos[17+$i])) {
						$dispo = explode("##", $infos[17+$i]);
						${'jour_'.$i} = $dispo[0];
						${'heure_'.$i} = $dispo[1];
					}
					else {
						break;
					}
				}
				
				if ($username != '' && $nom != '' && $mail != '' && $password != '') {
					$eleve->setActif(true);
					$eleve->setUsername($username);
					$eleve->setNom($nom);
					$eleve->setMail($mail);
					$eleve->setTelephone($telephone);
					$eleve->setSalt(time());
						$encoder = $factory->getEncoder($eleve);
						$pass = $encoder->encodePassword($password, $eleve->getSalt()); // $eleve->getPassword()   <=>   $form->get('password')->getData()
					$eleve->setPassword($pass);					
					$eleve->setLycee($lycee);
					
					$prog = $this->getDoctrine()
							     ->getManager()
							     ->getRepository('MajordeskAppBundle:Programme')
							     ->find($programme);
					$eleve->setProgramme($prog);
					
					for($i=1;$i<=7;$i++) {
						$disponibilite = new Disponibilite();	
						if (!empty(${'jour_'.$i}) && !empty(${'heure_'.$i})) {
							$disponibilite->setJour(${'jour_'.$i});
							$disponibilite->setHeureDebut(${'heure_'.$i});
							$eleve->addDisponibilite($disponibilite);
						}
						else {
							break;
						}
					}
					fwrite( $fp, "--------------------BDD--------------------\n");
					fwrite( $fp, "Création élève : OK\n");
				}
				else {
					fwrite( $fp, "--------------------BDD--------------------\n");
					fwrite( $fp, "Création élève : PAS OK\n");
				}
				
				/* CREATION FAMILLE */
				$famille = new Famille();
				$client = new Client();
				
				$pack =                          $infos[0];
				$gender =                        $infos[8];
				$username_famille =              $infos[9];
				$nom_famille =                   $infos[10];
				$mail_famille =                  $infos[11];
				$telephone_famille =             $infos[12];
				$password_famille =              $infos[13];
				$adresse =                       $infos[14];
				$code_postal =                   $infos[15];
				$ville =                         $infos[16];

				if ($pack != '' && $gender != '' && $username_famille != '' && $nom_famille != '' && $mail_famille != '' && $password_famille != '') {
					$client->setGender($gender);
					$client->setUsername($username_famille);
					$client->setNom($nom_famille);
					$client->setMail($mail_famille);
					$client->setTelephone($telephone_famille);
					$client->setSalt(time());
						$encoder = $factory->getEncoder($client);
						$pass_famille = $encoder->encodePassword($password_famille, $client->getSalt()); // à changer si l'on implémente une inscription simultannée de plusieurs parents
					$client->setPassword($pass_famille);
					$client->setAdresse($adresse);
					$client->setCodePostal($code_postal);
					$client->setVille($ville);				

					if ($pack == 21) {
						$famille->setHeuresAchetees(100);
						$famille->setHeuresRestantes(100);
						
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
					}
					else if ($pack == 23) {
						$famille->setHeuresAchetees(10);
						$famille->setHeuresRestantes(10);
						
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
					}
					else if ($pack == 24) {
						$famille->setHeuresAchetees(20);
						$famille->setHeuresRestantes(20);
						
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
					}
					else if ($pack == 33) {
						$famille->setHeuresAchetees(10);
						$famille->setHeuresRestantes(10);
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
											
							$eleve_matiere = new EleveMatiere();
							if ($id_matiere == 1) {				
								$eleve_matiere->setPlateforme(1);
								$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
									$data_abo->sub(new \DateInterval('P1D'));
								$eleve_matiere->setDateAbonnement($data_abo);
							}
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
					}
					else if ($pack == 34) {
						$famille->setHeuresAchetees(20);
						$famille->setHeuresRestantes(20);
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
											
							$eleve_matiere = new EleveMatiere();
							if ($id_matiere == 1) {				
								$eleve_matiere->setPlateforme(1);
								$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
									$data_abo->sub(new \DateInterval('P1D'));
								$eleve_matiere->setDateAbonnement($data_abo);
							}
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
					}
					$famille->addClient($client);
					$famille->addEleve($eleve);

					fwrite( $fp, "Création famille : OK\n");
					fwrite( $fp, "Création paiement...\n");
					$paiement = new Paiement();
					if ($pack == 21) {
						$paiement->setDescription('Vous avez acheté un pack de <em>10</em> heures de cours.');
					}
					else if ($pack == 23) {
						$paiement->setDescription('Vous avez acheté le pack <em>découverte 1 heure</em>.');
					}
					else if ($pack == 24) {
						$paiement->setDescription('Vous avez acheté le pack <em>découverte 2 heures</em>.');
					}
					else if ($pack == 33) {
						$paiement->setDescription('Vous avez acheté le pack <em>découverte 1 heure</em>.');
					}
					else if ($pack == 34) {
						$paiement->setDescription('Vous avez acheté le pack <em>découverte 2 heures</em>.');
					}
					$paiement->setPack($infos[0]);
					$paiement->setMontant($amount);
					$paiement->setTransaction($transaction_id);
					$famille->addPaiement($paiement);
					fwrite( $fp, "Création paiement: OK\n");
					
					fwrite( $fp, "Validation par Flush...\n");

					$em->persist($famille);
					$em->flush();
					fwrite( $fp, "Flush : OK\n");
				}
				else {
					fwrite( $fp, "Création famille : PAS OK\n");
				}
			}
		}

		fclose ($fp);
		
		return new Response();
	}
	
	
	public function inscriptionResponseAction()
    {
		$request = $this->getRequest();
		$session = $request->getSession();
		
		$DATA = $request->request->get('DATA');
		if (!empty($DATA)) {
			// Récupération de la variable cryptée DATA
			$message="message=".$DATA;

			// Initialisation du chemin du fichier pathfile (à modifier)
			//   ex :
			//    -> Windows : $pathfile="pathfile=c:/repertoire/pathfile";
			//    -> Unix    : $pathfile="pathfile=/home/repertoire/pathfile";
		   
		   $pathfile="pathfile=/home/majorcla/mercanet/payment/param/pathfile";

			// Initialisation du chemin de l'executable response (à modifier)
			// ex :
			// -> Windows : $path_bin = "c:/repertoire/bin/response";
			// -> Unix    : $path_bin = "/home/repertoire/bin/response";
			//

			$path_bin = "/home/majorcla/mercanet/payment/bin/response";

			// Appel du binaire response
			$message = escapeshellcmd($message);
			$result=exec("$path_bin $pathfile $message");


			//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
			//		- code=0	: la fonction retourne les données de la transaction dans les variables v1, v2, ...
			//				: Ces variables sont décrites dans le GUIDE DU PROGRAMMEUR
			//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error


			//	on separe les differents champs et on les met dans une variable tableau

			$tableau = explode ("!", $result);

			//	Récupération des données de la réponse

			$code = $tableau[1];
			$error = $tableau[2];
			$merchant_id = $tableau[3];
			$merchant_country = $tableau[4];
			$amount = $tableau[5];
			$transaction_id = $tableau[6];
			$payment_means = $tableau[7];
			$transmission_date= $tableau[8];
			$payment_time = $tableau[9];
			$payment_date = $tableau[10];
			$response_code = $tableau[11];
			$payment_certificate = $tableau[12];
			$authorisation_id = $tableau[13];
			$currency_code = $tableau[14];
			$card_number = $tableau[15];
			$cvv_flag = $tableau[16];
			$cvv_response_code = $tableau[17];
			$bank_response_code = $tableau[18];
			$complementary_code = $tableau[19];
			$complementary_info = $tableau[20];
			$return_context = $tableau[21];
			$caddie = $tableau[22];
			$receipt_complement = $tableau[23];
			$merchant_language = $tableau[24];
			$language = $tableau[25];
			$customer_id = $tableau[26];
			$order_id = $tableau[27];
			$customer_email = $tableau[28];
			$customer_ip_address = $tableau[29];
			$capture_day = $tableau[30];
			$capture_mode = $tableau[31];
			$data = $tableau[32];
			$order_validity = $tableau[33];  
			$transaction_condition = $tableau[34];
			$statement_reference = $tableau[35];
			$card_validity = $tableau[36];
			$score_value = $tableau[37];
			$score_color = $tableau[38];
			$score_info = $tableau[39];
			$score_threshold = $tableau[40];
			$score_profile = $tableau[41];
		
			$log_name = date('Y-m-d_H-i-s');
			
			if ($response_code == '00') {
				$logfile="/home/majorcla/mercanet/payment/logs/".$log_name."_success.txt";
			}
			else {
				$logfile="/home/majorcla/mercanet/payment/logs/".$log_name."_fail.txt";
			}

			// Ouverture du fichier de log en append

			$fp=fopen($logfile, "a");
		
			

			//  analyse du code retour

			if (( $code == "" ) && ( $error == "" ) )
			{
				fwrite( $fp, "erreur appel response\n");
				fwrite( $fp, "executable response non trouve $path_bin\n");
			}

			//	Erreur, affiche le message d'erreur

			else if ( $code != 0 ){
				fwrite( $fp, "Erreur appel API de paiement.\n\n");
				fwrite( $fp, "message erreur : $error\n\n");
			}

			// OK, affichage des champs de la réponse
			else {		
				fwrite( $fp, "----------------TRANSACTION----------------\n");
				fwrite( $fp, "merchant_id : $merchant_id\n");
				fwrite( $fp, "merchant_country : $merchant_country\n");
				fwrite( $fp, "amount : $amount\n");
				fwrite( $fp, "transaction_id : $transaction_id\n");
				fwrite( $fp, "transmission_date: $transmission_date\n");
				fwrite( $fp, "payment_means: $payment_means\n");
				fwrite( $fp, "payment_time : $payment_time\n");
				fwrite( $fp, "payment_date : $payment_date\n");
				fwrite( $fp, "response_code : $response_code\n");
				fwrite( $fp, "payment_certificate : $payment_certificate\n");
				fwrite( $fp, "authorisation_id : $authorisation_id\n");
				fwrite( $fp, "currency_code : $currency_code\n");
				fwrite( $fp, "card_number : $card_number\n");
				fwrite( $fp, "cvv_flag: $cvv_flag\n");
				fwrite( $fp, "cvv_response_code: $cvv_response_code\n");
				fwrite( $fp, "bank_response_code: $bank_response_code\n");
				fwrite( $fp, "complementary_code: $complementary_code\n");
				fwrite( $fp, "complementary_info: $complementary_info\n");
				fwrite( $fp, "return_context: $return_context\n");
				fwrite( $fp, "caddie : $caddie\n");
				fwrite( $fp, "receipt_complement: $receipt_complement\n");
				fwrite( $fp, "merchant_language: $merchant_language\n");
				fwrite( $fp, "language: $language\n");
				fwrite( $fp, "customer_id: $customer_id\n");
				fwrite( $fp, "order_id: $order_id\n");
				fwrite( $fp, "customer_email: $customer_email\n");
				fwrite( $fp, "customer_ip_address: $customer_ip_address\n");
				fwrite( $fp, "capture_day: $capture_day\n");
				fwrite( $fp, "capture_mode: $capture_mode\n");
				fwrite( $fp, "data: $data\n");
				fwrite( $fp, "order_validity: $order_validity\n");
				fwrite( $fp, "transaction_condition: $transaction_condition\n");
				fwrite( $fp, "statement_reference: $statement_reference\n");
				fwrite( $fp, "card_validity: $card_validity\n");
				fwrite( $fp, "card_validity: $score_value\n");
				fwrite( $fp, "card_validity: $score_color\n");
				fwrite( $fp, "card_validity: $score_info\n");
				fwrite( $fp, "card_validity: $score_threshold\n");
				fwrite( $fp, "card_validity: $score_profile\n\n");

				fwrite( $fp, "----------------INSCRIPTION----------------\n");
				$infos = unserialize(base64_decode($caddie));
				fwrite( $fp, "pack: ".$infos[0]."\n");
				fwrite( $fp, "prénom enfant: ".$infos[1]."\n");
				fwrite( $fp, "nom enfant: ".$infos[2]."\n");
				fwrite( $fp, "mail enfant: ".$infos[3]."\n");
				fwrite( $fp, "téléphone enfant: ".$infos[4]."\n");
				fwrite( $fp, "password enfant: ".$infos[5]."\n");
				fwrite( $fp, "lycée: ".$infos[6]."\n");
				fwrite( $fp, "programme: ".$infos[7]."\n");
				fwrite( $fp, "parenté: ".$infos[8]."\n");
				fwrite( $fp, "prénom parent: ".$infos[9]."\n");
				fwrite( $fp, "nom parent: ".$infos[10]."\n");
				fwrite( $fp, "mail parent: ".$infos[11]."\n");
				fwrite( $fp, "téléphone parent: ".$infos[12]."\n");
				fwrite( $fp, "password parent: ".$infos[13]."\n");
				fwrite( $fp, "adresse: ".$infos[14]."\n");
				fwrite( $fp, "code postal: ".$infos[15]."\n");
				fwrite( $fp, "ville: ".$infos[16]."\n");
				fwrite( $fp, "matieres: ".$infos[17]."\n");
				for($i=1;$i<=7;$i++) {
					if (!empty($infos[17+$i])) {
						fwrite( $fp, "dispo ".$i.": ".$infos[17+$i]."\n\n");
					}
					else {
						break;
					}
				}
				
				if ($response_code == '00' && $infos[0] == 21) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 10 heures de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives.<br><br>La connexion à vos plateformes s\'effectue depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 23) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 1 heure de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives.<br><br>La connexion à vos plateformes s\'effectue depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 24) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 2 heures de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives.<br><br>La connexion à vos plateformes s\'effectue depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 33) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 1 heure de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives.<br><br>La connexion à vos plateformes s\'effectue depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 34) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 2 heures de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives.<br><br>La connexion à vos plateformes s\'effectue depuis cette page.');
				}
				else {
					$session->getFlashBag()->add('warning-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Une erreur est survenue au cours de votre inscription.<br><br>Si le problème persiste, vous pouvez contacter le service client de Majorclass au 06.76.10.15.98.');
				}
			}
			fclose ($fp);
		}
		return $this->redirect($this->generateUrl('login'));
    }
	
	/**
	  * MERCANET ABONNEMENTS
	  */
	
	public function inscriptionAutoresponseSubAction()
    {
		$request = $this->get('request');
		$session = $this->get('session');
		
		// Récupération de la variable cryptée DATA
		$DATA = $request->request->get('DATA');
		$message="message=".$DATA;

		// Initialisation du chemin du fichier pathfile (à modifier)
			//   ex :
			//    -> Windows : $pathfile="pathfile=c:/repertoire/pathfile"
			//    -> Unix    : $pathfile="pathfile=/home/repertoire/pathfile"
			
		$pathfile="pathfile=/home/majorcla/mercanet/subscription/param/pathfile";

		//Initialisation du chemin de l'executable response (à modifier)
		//ex :
		//-> Windows : $path_bin = "c:/repertoire/bin/response"
		//-> Unix    : $path_bin = "/home/repertoire/bin/response"
		//

		$path_bin = "/home/majorcla/mercanet/subscription/bin/responseabo";

		// Appel du binaire response
		$message = escapeshellcmd($message);
		$result=exec("$path_bin $pathfile $message");

		//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
		//		- code=0	: la fonction retourne les données de la transaction dans les variables v1, v2, ...
		//				: Ces variables sont décrites dans le GUIDE DU PROGRAMMEUR
		//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error


		//	on separe les differents champs et on les met dans une variable tableau

		$tableau = explode ("!", $result);

		$code = $tableau[1];
		$error = $tableau[2];
		$merchant_id = $tableau[3];
		$transaction_id = $tableau[4];		
		$transmission_date = $tableau[5];		
		$sub_time = $tableau[6];			
		$sub_date = $tableau[7];				
		$response_code = $tableau[8];
		$bank_response_code = $tableau[9];				
		$cvv_response_code = $tableau[10];
		$cvv_flag = $tableau[11];
		$complementary_code = $tableau[12];				
		$complementary_info = $tableau[13];			
		$sub_payment_mean = $tableau[14];			
		$card_number = $tableau[15];
		$card_validity = $tableau[16];	
		$payment_certificate = $tableau[17];			
		$authorisation_id = $tableau[18];
		$currency_code = $tableau[19];
		$sub_type = $tableau[20];
		$sub_amount = $tableau[21];
		$capture_day = $tableau[22];
		$capture_mode = $tableau[23];
		$merchant_language = $tableau[24];
		$merchant_country = $tableau[25];
		$language = $tableau[26];					
		$receipt_complement = $tableau[27];				
		$caddie = $tableau[28];				  	
		$data = $tableau[29];
		$return_context = $tableau[30];
		$customer_ip_address = $tableau[31];
		$order_id = $tableau[32];
		$sub_operation_code = $tableau[33];

		$sub_subscriber_id = $tableau[34];
		$sub_civil_status = $tableau[35];
		$sub_lastname = $tableau[36];
		$sub_firstname = $tableau[37];
		$sub_address1 = $tableau[38];
		$sub_address2 = $tableau[39];
		$sub_zipcode = $tableau[40];
		$sub_city = $tableau[41];
		$sub_country = $tableau[42];
		$sub_telephone = $tableau[43];
		$sub_email = $tableau[44];
		$sub_description = $tableau[45];

		$log_name = date('Y-m-d_H-i-s');
		
		if ($response_code == '00') {
			$logfile="/home/majorcla/mercanet/subscription/autologs/".$log_name."_success.txt";
		}
		else {
			$logfile="/home/majorcla/mercanet/subscription/autologs/".$log_name."_fail.txt";
		}

		// Ouverture du fichier de log en append

		$fp=fopen($logfile, "a");

		//  analyse du code retour

	  if (( $code == "" ) && ( $error == "" ) )
		{
			fwrite( $fp, "erreur appel response\n");
			fwrite( $fp, "executable response non trouve $path_bin\n");
		}

		//	Erreur, sauvegarde le message d'erreur

		else if ( $code != 0 ){
			fwrite( $fp, " API call error.\n");
			fwrite( $fp, "Error message :  $error\n");
		}
		else {
			fwrite( $fp, "----------------TRANSACTION----------------\n");
			fwrite( $fp, "merchant_id : $merchant_id\n");
			fwrite( $fp, "transaction_id : $transaction_id\n");		
			fwrite( $fp, "transmission_date : $transmission_date\n");		
			fwrite( $fp, "sub_time : $sub_time\n");			
			fwrite( $fp, "sub_date : $sub_date\n");				
			fwrite( $fp, "response_code : $response_code\n");
			fwrite( $fp, "bank_response_code : $bank_response_code\n");				
			fwrite( $fp, "cvv_response_code : $cvv_response_code\n");
			fwrite( $fp, "cvv_flag : $cvv_flag\n");
			fwrite( $fp, "complementary_code : $complementary_code\n");				
			fwrite( $fp, "complementary_info : $complementary_info\n");			
			fwrite( $fp, "sub_payment_mean : $sub_payment_mean\n");			
			fwrite( $fp, "card_number : $card_number\n");
			fwrite( $fp, "card_validity : $card_validity\n");	
			fwrite( $fp, "payment_certificate : $payment_certificate\n");			
			fwrite( $fp, "authorisation_id : $authorisation_id\n");
			fwrite( $fp, "currency_code : $currency_code\n");
			fwrite( $fp, "sub_type : $sub_type\n");
			fwrite( $fp, "sub_amount : $sub_amount\n");
			fwrite( $fp, "capture_day : $capture_day\n");
			fwrite( $fp, "capture_mode : $capture_mode\n");
			fwrite( $fp, "merchant_language : $merchant_language\n");
			fwrite( $fp, "merchant_country : $merchant_country\n");
			fwrite( $fp, "language : $language\n");					
			fwrite( $fp, "receipt_complement : $receipt_complement\n");				
			fwrite( $fp, "caddie : $caddie\n");				  	
			fwrite( $fp, "data : $data\n");
			fwrite( $fp, "return_context : $return_context\n");
			fwrite( $fp, "customer_ip_address : $customer_ip_address\n");
			fwrite( $fp, "order_id : $order_id\n");
			fwrite( $fp, "sub_operation_code : $sub_operation_code\n");
			fwrite( $fp, "sub_subscriber_id : $sub_subscriber_id\n");
			fwrite( $fp, "sub_civil_status : $sub_civil_status\n");
			fwrite( $fp, "sub_lastname : $sub_lastname\n");
			fwrite( $fp, "sub_firstname : $sub_firstname\n");
			fwrite( $fp, "sub_address1 : $sub_address1\n");
			fwrite( $fp, "sub_address2 : $sub_address2\n");
			fwrite( $fp, "sub_zipcode : $sub_zipcode\n");
			fwrite( $fp, "sub_city : $sub_city\n");
			fwrite( $fp, "sub_country : $sub_country\n");
			fwrite( $fp, "sub_telephone : $sub_telephone\n");
			fwrite( $fp, "sub_email : $sub_email\n");
			fwrite( $fp, "sub_description : $sub_description\n\n");
			
			fwrite( $fp, "----------------INSCRIPTION----------------\n");
			$infos = unserialize(base64_decode($caddie));
			fwrite( $fp, "pack: ".$infos[0]."\n");
			fwrite( $fp, "prénom enfant: ".$infos[1]."\n");
			fwrite( $fp, "nom enfant: ".$infos[2]."\n");
			fwrite( $fp, "mail enfant: ".$infos[3]."\n");
			fwrite( $fp, "téléphone enfant: ".$infos[4]."\n");
			fwrite( $fp, "password enfant: ".$infos[5]."\n");
			fwrite( $fp, "lycée: ".$infos[6]."\n");
			fwrite( $fp, "programme: ".$infos[7]."\n");
			fwrite( $fp, "parenté: ".$infos[8]."\n");
			fwrite( $fp, "prénom parent: ".$infos[9]."\n");
			fwrite( $fp, "nom parent: ".$infos[10]."\n");
			fwrite( $fp, "mail parent: ".$infos[11]."\n");
			fwrite( $fp, "téléphone parent: ".$infos[12]."\n");
			fwrite( $fp, "password parent: ".$infos[13]."\n");
			fwrite( $fp, "adresse: ".$infos[14]."\n");
			fwrite( $fp, "code postal: ".$infos[15]."\n");
			fwrite( $fp, "ville: ".$infos[16]."\n");
			fwrite( $fp, "matieres: ".$infos[17]."\n");
			for($i=1;$i<=7;$i++) {
				if (!empty($infos[17+$i])) {
					fwrite( $fp, "dispo ".$i.": ".$infos[17+$i]."\n\n");
				}
				else {
					break;
				}
			}
			
			if ($response_code == '00') {	
				fwrite( $fp, "etape1\n");
				$em = $this->getDoctrine()->getManager();
				$factory = $this->get('security.encoder_factory');
				fwrite( $fp, "etape2\n");
				/* CREATION ELEVE */
				$eleve = new Eleve();
				
				$username =              $infos[1];
				$nom =                   $infos[2];
				$mail =                  $infos[3];
				$telephone =             $infos[4];
				$password =              $infos[5];
				$lycee =                 $infos[6];
				$programme =             $infos[7];
				fwrite( $fp, "etape3\n");
				$matieres =              explode("##", $infos[17]);
				$jour_1='';
				$jour_2='';
				$jour_3='';
				$jour_4='';
				$jour_5='';
				$jour_6='';
				$jour_7='';
				$heure_1='';
				$heure_2='';
				$heure_3='';
				$heure_4='';
				$heure_5='';
				$heure_6='';
				$heure_7='';
				for($i=1;$i<=7;$i++) {
					if (!empty($infos[17+$i])) {
						$dispo = explode("##", $infos[17+$i]);
						${'jour_'.$i} = $dispo[0];
						${'heure_'.$i} = $dispo[1];
					}
					else {
						break;
					}
				}
				fwrite( $fp, "etape4\n");
				
				if ($username != '' && $nom != '' && $mail != '' && $password != '') {
					$eleve->setActif(true);
					$eleve->setUsername($username);
					$eleve->setNom($nom);
					$eleve->setMail($mail);
					$eleve->setTelephone($telephone);
					$eleve->setSalt(time());
						$encoder = $factory->getEncoder($eleve);
						$pass = $encoder->encodePassword($password, $eleve->getSalt()); // $eleve->getPassword()   <=>   $form->get('password')->getData()
					fwrite( $fp, "etape5\n");
					$eleve->setPassword($pass);	
					fwrite( $fp, "etape6\n");					
					$eleve->setLycee($lycee);
					
					$prog = $this->getDoctrine()
							     ->getManager()
							     ->getRepository('MajordeskAppBundle:Programme')
							     ->find($programme);
					$eleve->setProgramme($prog);
					
					for($i=1;$i<=7;$i++) {
						$disponibilite = new Disponibilite();	
						if (!empty(${'jour_'.$i}) && !empty(${'heure_'.$i})) {
							$disponibilite->setJour(${'jour_'.$i});
							$disponibilite->setHeureDebut(${'heure_'.$i});
							$eleve->addDisponibilite($disponibilite);
						}
						else {
							break;
						}
					}
					fwrite( $fp, "--------------------BDD--------------------\n");
					fwrite( $fp, "Création élève : OK\n");
				}
				else {
					fwrite( $fp, "--------------------BDD--------------------\n");
					fwrite( $fp, "Création élève : PAS OK\n");
				}
				
				/* CREATION FAMILLE */
				$famille = new Famille();
				$client = new Client();
				
				$pack =                          $infos[0];
				$gender =                        $infos[8];
				$username_famille =              $infos[9];
				$nom_famille =                   $infos[10];
				$mail_famille =                  $infos[11];
				$telephone_famille =             $infos[12];
				$password_famille =              $infos[13];
				$adresse =                       $infos[14];
				$code_postal =                   $infos[15];
				$ville =                         $infos[16];
				

				if ($pack != '' && $gender != '' && $username_famille != '' && $nom_famille != '' && $mail_famille != '' && $password_famille != '') {
					fwrite( $fp, "etape7\n");
					$client->setGender($gender);
					$client->setUsername($username_famille);
					$client->setNom($nom_famille);
					$client->setMail($mail_famille);
					$client->setTelephone($telephone_famille);
					$client->setSalt(time());
						$encoder = $factory->getEncoder($client);
						$pass_famille = $encoder->encodePassword($password_famille, $client->getSalt()); // à changer si l'on implémente une inscription simultannée de plusieurs parents
					fwrite( $fp, "etape8\n");
					$client->setPassword($pass_famille);
					$client->setAdresse($adresse);
					$client->setCodePostal($code_postal);
					$client->setVille($ville);				
					
					if ($pack == 11) {
						fwrite( $fp, "etape9\n");
						foreach($matieres as $id_matiere) {
							if ($id_matiere == 1) {
								$matiere = $this->getDoctrine()
												->getManager()
												->getRepository('MajordeskAppBundle:Matiere')
												->find($id_matiere);
								$eleve_matiere = new EleveMatiere();
								$eleve_matiere->setPlateforme(1);
								$eleve_matiere->setPrelevementPlateforme(1);
								$matiere->addEleveMatiere($eleve_matiere);
								$eleve->addEleveMatiere($eleve_matiere);
								$em->persist($matiere);
								$em->persist($eleve_matiere);
							}
						}
						fwrite( $fp, "etape10\n");
					} else if ($pack == 22) {
						fwrite( $fp, "etape11\n");
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							$eleve_matiere->setCours(1);
							$eleve_matiere->setPrelevementCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
						fwrite( $fp, "etape12\n");
					} else if ($pack == 31) {
						fwrite( $fp, "etape13\n");
						$famille->setHeuresAchetees(100);
						$famille->setHeuresRestantes(100);
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							if ($id_matiere == 1) {
								$eleve_matiere->setPlateforme(1);
								$eleve_matiere->setPrelevementPlateforme(1);
								$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
									$data_abo->add(new \DateInterval('P1M'));
								$eleve_matiere->setDateAbonnement($data_abo);
							}
							$eleve_matiere->setCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}
						fwrite( $fp, "etape14\n");
					} else if ($pack == 32) {
						fwrite( $fp, "etape15\n");
						foreach($matieres as $id_matiere) {
							$matiere = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:Matiere')
											->find($id_matiere);
							$eleve_matiere = new EleveMatiere();
							if ($id_matiere == 1) {
								$eleve_matiere->setPlateforme(1);
								$eleve_matiere->setPrelevementPlateforme(1);
								$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
									$data_abo->add(new \DateInterval('P1M'));
								$eleve_matiere->setDateAbonnement($data_abo);
							}
							$eleve_matiere->setCours(1);
							$eleve_matiere->setPrelevementCours(1);
							$matiere->addEleveMatiere($eleve_matiere);
							$eleve->addEleveMatiere($eleve_matiere);
							$em->persist($matiere);
							$em->persist($eleve_matiere);
						}		
						fwrite( $fp, "etape16\n");						
					}
					$famille->setAbonnement($sub_subscriber_id);				
					$famille->addClient($client);
					$famille->addEleve($eleve);

					fwrite( $fp, "Création famille : OK\n");
					
					fwrite( $fp, "Création paiement...\n");
					$paiement = new Paiement();
					if ($pack == 11) {
						$paiement->setDescription('Vous vous êtes abonné à la Plateforme <span class="label label-info">Mathématiques</span>');
					} else if ($pack == 22) {
						$paiement->setDescription('Vous avez choisi le formule "à la carte".');
					} else if ($pack == 31) {
						$paiement->setDescription('Vous vous êtes abonné à la Plateforme <span class="label label-info">Mathématiques</span><br>Vous avez acheté un pack de <em>10</em> heures de cours.');
					} else if ($pack == 32) {
						$paiement->setDescription('Vous vous êtes abonné à la Plateforme <span class="label label-info">Mathématiques</span><br>Vous avez choisi le formule "à la carte".');
					}
					$paiement->setPack($infos[0]);
					$paiement->setMontant($sub_amount);
					$paiement->setTransaction($transaction_id);
					$famille->addPaiement($paiement);
					fwrite( $fp, "Création paiement: OK\n");
					
					fwrite( $fp, "Validation par Flush...\n");
					$em->persist($famille);
					$em->flush();
					fwrite( $fp, "Flush : OK\n");
				}
				else {
					fwrite( $fp, "Création famille : PAS OK\n");
				}
			}
		}

		fclose ($fp);
		
		return new Response();
	}
	
	
	public function inscriptionResponseSubAction()
    {
		$request = $this->getRequest();
		$session = $request->getSession();
		
		$DATA = $request->request->get('DATA');
		if (!empty($DATA)) {
			// Récupération de la variable cryptée DATA
			$message="message=".$DATA;

			// Initialisation du chemin du fichier pathfile (à modifier)
			//   ex :
			//    -> Windows : $pathfile="pathfile=c:/repertoire/pathfile";
			//    -> Unix    : $pathfile="pathfile=/home/repertoire/pathfile";
		   
		   $pathfile="pathfile=/home/majorcla/mercanet/subscription/param/pathfile";

			// Initialisation du chemin de l'executable response (à modifier)
			// ex :
			// -> Windows : $path_bin = "c:/repertoire/bin/response";
			// -> Unix    : $path_bin = "/home/repertoire/bin/response";
			//

			$path_bin = "/home/majorcla/mercanet/subscription/bin/responseabo";

			// Appel du binaire response
			$message = escapeshellcmd($message);
			$result=exec("$path_bin $pathfile $message");


			//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
			//		- code=0	: la fonction retourne les données de la transaction dans les variables v1, v2, ...
			//				: Ces variables sont décrites dans le GUIDE DU PROGRAMMEUR
			//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error


			//	on separe les differents champs et on les met dans une variable tableau

			$tableau = explode ("!", $result);

			//	Récupération des données de la réponse

			$code = $tableau[1];
			$error = $tableau[2];
			$merchant_id = $tableau[3];
			$transaction_id = $tableau[4];		
			$transmission_date = $tableau[5];		
			$sub_time = $tableau[6];			
			$sub_date = $tableau[7];				
			$response_code = $tableau[8];
			$bank_response_code = $tableau[9];				
			$cvv_response_code = $tableau[10];
			$cvv_flag = $tableau[11];
			$complementary_code = $tableau[12];				
			$complementary_info = $tableau[13];			
			$sub_payment_mean = $tableau[14];			
			$card_number = $tableau[15];
			$card_validity = $tableau[16];	
			$payment_certificate = $tableau[17];			
			$authorisation_id = $tableau[18];
			$currency_code = $tableau[19];
			$sub_type = $tableau[20];
			$sub_amount = $tableau[21];
			$capture_day = $tableau[22];
			$capture_mode = $tableau[23];
			$merchant_language = $tableau[24];
			$merchant_country = $tableau[25];
			$language = $tableau[26];					
			$receipt_complement = $tableau[27];				
			$caddie = $tableau[28];				  	
			$data = $tableau[29];
			$return_context = $tableau[30];
			$customer_ip_address = $tableau[31];
			$order_id = $tableau[32];
			$sub_operation_code = $tableau[33];

			$sub_subscriber_id = $tableau[34];
			$sub_civil_status = $tableau[35];
			$sub_lastname = $tableau[36];
			$sub_firstname = $tableau[37];
			$sub_address1 = $tableau[38];
			$sub_address2 = $tableau[39];
			$sub_zipcode = $tableau[40];
			$sub_city = $tableau[41];
			$sub_country = $tableau[42];
			$sub_telephone = $tableau[43];
			$sub_email = $tableau[44];
			$sub_description = $tableau[45];
		
			$log_name = date('Y-m-d_H-i-s');
			
			if ($response_code == '00') {
				$logfile="/home/majorcla/mercanet/subscription/logs/".$log_name."_success.txt";
			}
			else {
				$logfile="/home/majorcla/mercanet/subscription/logs/".$log_name."_fail.txt";
			}

			// Ouverture du fichier de log en append

			$fp=fopen($logfile, "a");
		
			

			//  analyse du code retour

			if (( $code == "" ) && ( $error == "" ) )
			{
				fwrite( $fp, "erreur appel response\n");
				fwrite( $fp, "executable response non trouve $path_bin\n");
			}

			//	Erreur, affiche le message d'erreur

			else if ( $code != 0 ){
				fwrite( $fp, "Erreur appel API de paiement.\n\n");
				fwrite( $fp, "message erreur : $error\n\n");
			}

			// OK, affichage des champs de la réponse
			else {		
				fwrite( $fp, "----------------TRANSACTION----------------\n");
				fwrite( $fp, "merchant_id : $merchant_id\n");
				fwrite( $fp, "transaction_id : $transaction_id\n");		
				fwrite( $fp, "transmission_date : $transmission_date\n");		
				fwrite( $fp, "sub_time : $sub_time\n");			
				fwrite( $fp, "sub_date : $sub_date\n");				
				fwrite( $fp, "response_code : $response_code\n");
				fwrite( $fp, "bank_response_code : $bank_response_code\n");				
				fwrite( $fp, "cvv_response_code : $cvv_response_code\n");
				fwrite( $fp, "cvv_flag : $cvv_flag\n");
				fwrite( $fp, "complementary_code : $complementary_code\n");				
				fwrite( $fp, "complementary_info : $complementary_info\n");			
				fwrite( $fp, "sub_payment_mean : $sub_payment_mean\n");			
				fwrite( $fp, "card_number : $card_number\n");
				fwrite( $fp, "card_validity : $card_validity\n");	
				fwrite( $fp, "payment_certificate : $payment_certificate\n");			
				fwrite( $fp, "authorisation_id : $authorisation_id\n");
				fwrite( $fp, "currency_code : $currency_code\n");
				fwrite( $fp, "sub_type : $sub_type\n");
				fwrite( $fp, "sub_amount : $sub_amount\n");
				fwrite( $fp, "capture_day : $capture_day\n");
				fwrite( $fp, "capture_mode : $capture_mode\n");
				fwrite( $fp, "merchant_language : $merchant_language\n");
				fwrite( $fp, "merchant_country : $merchant_country\n");
				fwrite( $fp, "language : $language\n");					
				fwrite( $fp, "receipt_complement : $receipt_complement\n");				
				fwrite( $fp, "caddie : $caddie\n");				  	
				fwrite( $fp, "data : $data\n");
				fwrite( $fp, "return_context : $return_context\n");
				fwrite( $fp, "customer_ip_address : $customer_ip_address\n");
				fwrite( $fp, "order_id : $order_id\n");
				fwrite( $fp, "sub_operation_code : $sub_operation_code\n");
				fwrite( $fp, "sub_subscriber_id : $sub_subscriber_id\n");
				fwrite( $fp, "sub_civil_status : $sub_civil_status\n");
				fwrite( $fp, "sub_lastname : $sub_lastname\n");
				fwrite( $fp, "sub_firstname : $sub_firstname\n");
				fwrite( $fp, "sub_address1 : $sub_address1\n");
				fwrite( $fp, "sub_address2 : $sub_address2\n");
				fwrite( $fp, "sub_zipcode : $sub_zipcode\n");
				fwrite( $fp, "sub_city : $sub_city\n");
				fwrite( $fp, "sub_country : $sub_country\n");
				fwrite( $fp, "sub_telephone : $sub_telephone\n");
				fwrite( $fp, "sub_email : $sub_email\n");
				fwrite( $fp, "sub_description : $sub_description\n\n");

				$infos = unserialize(base64_decode($caddie));
				fwrite( $fp, "pack: ".$infos[0]."\n");
				fwrite( $fp, "prénom enfant: ".$infos[1]."\n");
				fwrite( $fp, "nom enfant: ".$infos[2]."\n");
				fwrite( $fp, "mail enfant: ".$infos[3]."\n");
				fwrite( $fp, "téléphone enfant: ".$infos[4]."\n");
				fwrite( $fp, "password enfant: ".$infos[5]."\n");
				fwrite( $fp, "lycée: ".$infos[6]."\n");
				fwrite( $fp, "programme: ".$infos[7]."\n");
				fwrite( $fp, "parenté: ".$infos[8]."\n");
				fwrite( $fp, "prénom parent: ".$infos[9]."\n");
				fwrite( $fp, "nom parent: ".$infos[10]."\n");
				fwrite( $fp, "mail parent: ".$infos[11]."\n");
				fwrite( $fp, "téléphone parent: ".$infos[12]."\n");
				fwrite( $fp, "password parent: ".$infos[13]."\n");
				fwrite( $fp, "adresse: ".$infos[14]."\n");
				fwrite( $fp, "code postal: ".$infos[15]."\n");
				fwrite( $fp, "ville: ".$infos[16]."\n");
				fwrite( $fp, "matieres: ".$infos[17]."\n");
				for($i=1;$i<=7;$i++) {
					if (!empty($infos[17+$i])) {
						fwrite( $fp, "dispo ".$i.": ".$infos[17+$i]."\n\n");
					}
					else {
						break;
					}
				}
				if ($response_code == '00' && $infos[0] == 11) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre enfant et vous avez dès à présent accès à vos plateformes respectives. Vous pouvez vous connecter depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 22) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre enfant et vous avez dès à présent accès à vos plateformes respectives. Vous pouvez vous connecter depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 31) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre compte a bien été crédité de 10 heures de cours. Votre enfant et vous avez dès à présent accès à vos plateformes respectives. <br><br>Vous pouvez vous connecter depuis cette page.');
				}
				else if ($response_code == '00' && $infos[0] == 32) {
					$session->getFlashBag()->add('info-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Votre inscription s\'est déroulée avec succès !<br><br>Votre enfant et vous avez dès à présent accès à vos plateformes respectives. Vous pouvez vous connecter depuis cette page.');
				}
				else {
					$session->getFlashBag()->add('warning-inscription', '<i class="icon-info-sign"></i> <strong>Info :</strong> Une erreur est survenue au cours de votre inscription.<br><br>Si le problème persiste, vous pouvez contacter le service client de Majorclass au 06.76.10.15.98.');
				}
			}
			fclose ($fp);
		}
		return $this->redirect($this->generateUrl('login'));
    }
}
