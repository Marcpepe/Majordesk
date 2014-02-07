<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Response;

use Majordesk\AppBundle\Entity\CalEvent;
use Majordesk\AppBundle\Entity\Ticket;
use Majordesk\AppBundle\Entity\Paiement;
use Majordesk\AppBundle\Entity\Exercice;
use Majordesk\AppBundle\Entity\Question;
use Majordesk\AppBundle\Entity\Casier;
use Majordesk\AppBundle\Entity\CarteEtudiant;
use Majordesk\AppBundle\Entity\Contrat;

use Majordesk\AppBundle\Form\Type\CoursType;
use Majordesk\AppBundle\Form\Type\CoursFilterType;
use Majordesk\AppBundle\Form\Type\TicketType;
use Majordesk\AppBundle\Form\Type\TicketNoFiltreType;
use Majordesk\AppBundle\Form\Type\PasswordType;
use Majordesk\AppBundle\Form\Type\ProfInfoType;

class EleveController extends Controller
{

/* INDEX */

	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
	public function indexEleveAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			
			$user = $this->getUser();
			$professeurs = $user->getProfesseurs();
			$famille = $user->getFamille();
			$heuresRestantes = $famille->getHeuresRestantes();
			$filtre = $famille->getFiltre();
			
			$ticket = new Ticket();
			$ticket->setEleve($user);
			if ($filtre) {
				$form = $this->createForm( new TicketType($user->getId()), $ticket );
			} else {
				$form = $this->createForm( new TicketNoFiltreType($user->getId()), $ticket );
			}
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{
					$paymentAuthorized = false;
					if ($filtre) {
						$passparent = $form->get('passparent')->getData();
						$factory = $this->get('security.encoder_factory');
						$parents = $famille->getClients();
						foreach($parents as $parent) {
							$encoder = $factory->getEncoder($parent);			
							$encoded_pass = $encoder->encodePassword($passparent, $parent->getSalt());

							if ($encoded_pass == $parent->getPassword()) {
								$paymentAuthorized = true;
								break;
							}
						}
					}
					
					$eleve = $user;					
					$quantite = $ticket->getQuantite();
					$matiere = $form->get('matiere')->getData();
					
					$eleve_matiere = $this->getDoctrine()
										  ->getManager()
										  ->getRepository('MajordeskAppBundle:EleveMatiere')
										  ->getEleveMatiereByEleveAndMatiere($eleve->getId(), $matiere->getId());
					if (empty($eleve_matiere)) {
						throw new \Exception('eleve_matiere ne devrait pas être null');
					}
					$prelevementCours = $eleve_matiere->getPrelevementCours();
					
					if ($quantite == 10) {
						$temps = '1h';
					} else if ($quantite == 15) {
						$temps = '1h30';
					} else if ($quantite == 20) {
						$temps = '2h';
					} else if ($quantite == 25) {
						$temps = '2h30';
					} else if ($quantite == 30) {
						$temps = '3h';
					} else if ($quantite == 35) {
						$temps = '3h30';
					} else if ($quantite == 40) {
						$temps = '4h';
					} else if ($quantite == 45) {
						$temps = '4h30';
					} else if ($quantite == 50) {
						$temps = '5h';
					} else {
						$temps = 'n.c.';
					}
					$heuresReellesRestantes = $heuresRestantes / 10;
					$heuresIncrementees = $heuresReellesRestantes - $quantite / 10;
					$em = $this->getDoctrine()->getManager();
					
					if ($paymentAuthorized || $filtre == false) {
						$professeur = $ticket->getProfesseur();
						$professeur->setHeuresDonnees($professeur->getHeuresDonnees() + $quantite);
						if ($quantite <= $heuresRestantes) {
							$ticket->setRegle(true);
							$paiement = new Paiement();
							$paiement->setDescription($eleve->getUsername().' a pris un cours de '.$temps.'.<br>Il vous reste <strong>'.$heuresIncrementees.'</strong> heure(s) de cours.');
							$paiement->setFamille($famille);
							$paiement->setPack('1'.$quantite);
							$paiement->setMontant(0);
							$paiement->setTransaction(2);  // 0: annulé, 1: en cours, 2: validé, 3:ticket non réglé
							$paiement->setTicket($ticket);
							
							$famille->setHeuresRestantes($heuresRestantes - $quantite);
							$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
							$eleve->setHeuresPrises($eleve->getHeuresPrises() + $quantite);
							$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
							
							$em->persist($eleve_matiere);
							$em->persist($paiement);
							$em->persist($famille);
						}
						else if ($prelevementCours == 1) {
							if ($heuresRestantes > 0) {
								$ticket->setRegle(false);
								
								$famille->setHeuresRestantes(0);
								
								$quantiteRestanteADebiter = $quantite - $heuresRestantes;

								$paiement = new Paiement();
								$paiement->setDescription($eleve->getUsername().' a pris un cours de '.$temps.'.<br>Il vous restait '.$heuresReellesRestantes.' heure(s) qui sont maintenant épuisée(s).<br>Le complément de paiement va être effectué avec votre numéro d\'abonné.');
								$paiement->setFamille($famille);
								$paiement->setPack('2'.$quantiteRestanteADebiter);
								$paiement->setMontant(599.0*$quantiteRestanteADebiter);
								$paiement->setTransaction(1);
								$paiement->setTicket($ticket);
								
								$famille->setHeuresAchetees($famille->getHeuresAchetees() + $quantiteRestanteADebiter);
								$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
								$eleve->setHeuresPrises($eleve->getHeuresPrises() + $quantite);
								$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
								
								$em->persist($eleve_matiere);
								$em->persist($paiement);
								$em->persist($famille);
							}
							else {
								$ticket->setRegle(false);
							
								$paiement = new Paiement();
								$paiement->setDescription($eleve->getUsername().' a pris un cours de '.$temps.'.');
								$paiement->setFamille($famille);
								$paiement->setPack('2'.$quantite);
								$paiement->setMontant(599.0*$quantite);
								$paiement->setTransaction(1);
								$paiement->setTicket($ticket);
								
								$famille->setHeuresAchetees($famille->getHeuresAchetees() + $quantite);
								$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
								$eleve->setHeuresPrises($eleve->getHeuresPrises() + $quantite);
								$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
								
								$em->persist($eleve_matiere);
								$em->persist($paiement);
								$em->persist($famille);
							}
						}
						else {
							$ticket->setRegle(false);
							
							$paiement = new Paiement();
							$paiement->setDescription($eleve->getUsername().' a pris un cours de '.$temps.'.<br>Il ne vous reste pas suffisamment d\'heures pour payer ce cours.<br><strong>Veuillez recréditer votre compte.</strong>');
							$paiement->setFamille($famille);
							$paiement->setPack('3'.$quantite);
							$paiement->setMontant(599.0*$quantite);
							$paiement->setTransaction(3);
							$paiement->setTicket($ticket);
							
							$famille->setHeuresRestantes($heuresRestantes - $quantite);
							$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
							$eleve->setHeuresPrises($eleve->getHeuresPrises() + $quantite);
							$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
							
							$em->persist($eleve_matiere);
							$em->persist($paiement);
							$em->persist($famille);
						}
						
						$em->persist($ticket);
						$em->persist($professeur);
						
						$em->flush();
						$this->get('session')->getFlashBag()->add('info', ' Le cours a bien été déclaré.');
						return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
					}
					else {
						$session->getFlashBag()->add('warning', ' Mot de passe du parent incorrect.');
					}
				}
				else {
					// $errors = $form->getErrorsAsString();
					$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
				}
			}
			}
			
			return $this->render('MajordeskAppBundle:Eleve:index-eleve.html.twig', array(
				'form' => $form->createView(),
				'professeurs' => $professeurs,
				'famille' => $famille,
			));
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function recommandezNousAction()
    {
		$user = $this->getUser();	
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$defaultData = array('destinataire'=>'', 'corps' => "Salut,\n\nSi tu cherches un bon prof particulier, je te recommande Majorclass !\n\nPersonnellement il m'ont vraiment aidé. Leur site est : www.majorclass.fr\n\nA plus!");
		} else {
			$defaultData = array('destinataire'=>'', 'corps' => "Bonjour,\n\nSi vous cherchez un professeur particulier de qualité pour votre enfant, en Mathématiques ou Physique/Chimie, Majorclass est ce qu'il vous faut !\n\nLeurs professeurs sont sélectionnés parmi des étudiants de Grandes Ecoles expérimentés et je suis pour ma part très satisfait(e) de la qualité de leurs services.\n\nJe vous les recommande vivement. Leur site est www.majorclass.fr.\n\nA bientôt!");
		}
		
		$form = $this->createFormBuilder($defaultData)
							->add('destinataire', 'email', array(
								'attr' => array('class'=>'form-control', 'placeholder' =>'Email d\'un ami')
							))
							->add('corps', 'textarea', array(
								'attr' => array('class'=>'form-control', 'rows'=>10)
							))
							// ->add('send', 'submit')
							->getForm();
				
		
		$request = $this->getRequest();
		$form->handleRequest($request);
		
		if ($form->isValid()) 
		{
			$data = $form->getData();
			$destinataire = $data['destinataire'];
			$corps = $data['corps'];	
			$corps = nl2br($corps);
			
			$message = \Swift_Message::newInstance()
							->setSubject($user->getUsername().' vous recommande Majorclass : les cours particuliers de qualité')
							->setFrom($user->getMail())
							->setTo($destinataire)
							->setBody($this->renderView('MajordeskAppBundle:Template:recommandation.html.twig', array('corps'=>$corps)), 'text/html')
						;
						$this->get('mailer')->send($message);
			
			$message = \Swift_Message::newInstance()
							->setSubject('Nouvelle recommandation')
							->setFrom('recommandation@majorclass.fr')
							->setTo(array('marc@majorclass.fr','jonathan@majorclass.fr'))
							->setBody($this->renderView('MajordeskAppBundle:Template:recommandation.html.twig', array('corps'=>$corps)), 'text/html')
						;
						$this->get('mailer')->send($message);
			
			$this->get('session')->getFlashBag()->add('info', ' Mail envoyé');
		}
		
		return $this->render('MajordeskAppBundle:Eleve:recommandez-nous.html.twig', array(
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function profilAction()
    {
		$user = $this->getUser();	
		
		if (!$this->get('security.context')->isGranted('ROLE_PROF') && $this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$eleves = $user->getFamille()->getEleves();
			
			return $this->render('MajordeskAppBundle:Eleve:profil.html.twig', array(
				'user' => $user,
				'eleves' => $eleves,
			));
		}
		else if ($this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			
			$user = $this->getUser();

			$form = $this->createForm( new ProfInfoType(), $user );

			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) {
					$em = $this->getDoctrine()->getManager();
					$casier = $user->getCasier();
					if (!empty($casier)) {
						$casier->preUpload();
						$casier->setProfesseur($user);
					}
					$carteEtudiant = $user->getCarteEtudiant();
					if (!empty($carteEtudiant)) {
						$carteEtudiant->preUpload();
						$carteEtudiant->setProfesseur($user);
					}
					$contrat = $user->getContrat();
					if (!empty($contrat)) {
						$contrat->preUpload();
						$contrat->setProfesseur($user);
					}
					$em->persist($user);
					$em->flush();
					
					$this->get('session')->getFlashBag()->add('info', ' Tes documents et informations ont bien été enregistrés.');
					return $this->redirect($this->generateUrl('majordesk_app_profil'));
				}
				else {
					$this->get('session')->getFlashBag()->add('warning', ' Une erreur est survenue :');
				}
			}
			
			return $this->render('MajordeskAppBundle:Eleve:profil.html.twig', array(
				'user' => $user,
				'form' => $form->createView(),
			));
		}
		else {
			return $this->render('MajordeskAppBundle:Eleve:profil.html.twig', array(
				'user' => $user
			));
		}
		
		
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function modifierPasswordAction()
    {
		$user = $this->getUser();	
		$form = $this->createForm( new PasswordType );
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{				
				$oldpass = $form->get('oldpass')->getData();
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($user);			
				$encoded_pass = $encoder->encodePassword($oldpass, $user->getSalt());

				if ($encoded_pass == $user->getPassword()) {
					$newpass = $form->get('newpass')->getData();
					$password = $encoder->encodePassword($newpass, $user->getSalt());
					$user->setPassword($password);
					
					$em = $this->getDoctrine()->getManager();
					$em->persist($user);
					$em->flush();
					
					$this->get('session')->getFlashBag()->add('info', 'Nouveau mot de passe enregistré.');
					return $this->redirect($this->generateUrl('majordesk_app_profil'));
				}
				$this->get('session')->getFlashBag()->add('warning', 'L\'ancien de mot de passe est incorrect.');
			}
			else {
				$this->get('session')->getFlashBag()->add('warning', 'Un des champs n\'est pas rempli correctement.');
			}
		}
						
		return $this->render('MajordeskAppBundle:Eleve:modifier-password.html.twig', array(
			'form' => $form->createView()
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function consulterFavorisAction()
    {
		$statut_resolu = $this->container->getParameter('statut_resolu');
		$user = $this->getUser();	
						
		$favoris = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Exercice')
						->getFavorisByEleve($user->getId(), $statut_resolu);
						
		return $this->render('MajordeskAppBundle:Eleve:consulter-favoris.html.twig', array(
			'favoris' => $favoris
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function consulterResumesAction()
    {
		return $this->render('MajordeskAppBundle:Eleve:consulter-resumes.html.twig');
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function afficherResumeAction($id_chapitre)
    {
		$chapitre = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Chapitre')
						 ->find($id_chapitre);
		
		if (empty($chapitre)) {
			throw new \Exception('Le chapitre recherché n\'existe pas !');
		}
		
		$parties = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Partie')
						->getPartiesByChapitre($id_chapitre);
		
		$resume = array();
		
		foreach($parties as $partie) {
			$mod_exercice = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModExercice')
								 ->getOneTutocours($partie->getId());
			if ($mod_exercice != null) {
				$id_mod_exercice = $mod_exercice->getId();	
				
				$mod_macros_exercice = $this->getDoctrine()
											->getManager()
											->getRepository('MajordeskAppBundle:ModMacro')
											->getOrderedModMacrosExerciceByModExerciceId($id_mod_exercice);
				
				$mod_elements_exercice = $this->getDoctrine()
											  ->getManager()
											  ->getRepository('MajordeskAppBundle:ModElement')
											  ->getOrderedModElementsExerciceByModExerciceId($id_mod_exercice);
											  
				$resume[$partie->getId()]['mod_macros_exercice'] = $mod_macros_exercice;
				$resume[$partie->getId()]['mod_elements_exercice'] = $mod_elements_exercice;
			}
		}
		
		return $this->render('MajordeskAppBundle:Eleve:afficher-resume.html.twig', array(
			'chapitre' => $chapitre,
			'parties' => $parties,
			'resume' => $resume
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function consulterFichesAction()
    {
		return $this->render('MajordeskAppBundle:Eleve:consulter-fiches.html.twig');
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function afficherFicheAction($id_matiere, $id_programme, $chapitre)
    {
		$file_path = '/home/majorcla/fiches/'.$id_matiere.'-'.$id_programme.'/'.$id_matiere.'-'.$id_programme.'-'.$chapitre.'.pdf';
		
		return new Response(file_get_contents($file_path), 200, array(
			'Content-Type' => 'application/pdf'
		));
    }
	

/* CHAPITRES */

	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function chapitresAction()
    {
		$user = $this->getUser();
		
		$programme = $user->getProgramme();
		$matieres = $user->getMatieres();
		$chapitres = array();
		
		foreach($matieres as $matiere) {
			$chapitres = array_merge( $chapitres, $this->getDoctrine()
													   ->getManager()
													   ->getRepository('MajordeskAppBundle:Chapitre')
													   ->getChapitresByMatiereAndProgramme($matiere->getId(), $programme->getId())
									);
		}
	
        return $this->render('MajordeskAppBundle:Eleve:eleve-chapitres.html.twig', array(
			'matieres' => $matieres,
			'chapitres' => $chapitres
		));
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function selectChapitreForQueueAction($id_matiere)
    {
		$user = $this->getUser();
		$programme = $user->getProgramme();
		$chapitres = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Chapitre')
						  ->getChapitresByMatiereAndProgramme($id_matiere, $programme->getId());
	
        return $this->render('MajordeskAppBundle:Eleve:eleve-chapitre-selection-queue.html.twig', array(
			'chapitres' => $chapitres,
			'id_matiere' => $id_matiere
		));
    }
	
/* PARTIES */
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function partiesAction($id_chapitre)
    {
		$user = $this->getUser();
		
		$chapitre = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Chapitre')
				        ->find($id_chapitre);
		
		$parties = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Partie')
				        ->getPartiesByChapitre($id_chapitre);
						
		$exercices = $this->getDoctrine()
						  ->getManager()
					      ->getRepository('MajordeskAppBundle:Exercice')
				          ->getExercicesByChapitre($user->getId(), $id_chapitre);
	
        return $this->render('MajordeskAppBundle:Eleve:eleve-parties.html.twig', array(
			'chapitre' => $chapitre,
			'parties' => $parties,
			'exercices' => $exercices
		));
    }
	
/* EXERCICES */
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function rechercheExercicesAction($id_matiere)
    {
		$session = $this->get('session');
		$etape_cours = $session->get('etape_cours');
		if ( $etape_cours == 1 ) {
			$session->set('etape_cours', 2);
		}
		$user = $this->getUser();
		$programme = $user->getProgramme();
		$chapitres = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Chapitre')
				          ->getChapitresByMatiereAndProgramme($id_matiere, $programme->getId());
		$parties = $this->getDoctrine()
						->getManager()
						->getRepository('MajordeskAppBundle:Partie')
				        ->getPartiesByMatiereAndProgramme($id_matiere, $programme->getId());
		return $this->render('MajordeskAppBundle:Eleve:recherche-exercices.html.twig', array(
			'programme' => $programme,
			'chapitres' => $chapitres,
			'parties' => $parties,
		));
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    // public function previewExerciceAction($id_exercice)
    // {
		// $mod_exercice = $this->getDoctrine()
							 // ->getManager()
						     // ->getRepository('MajordeskAppBundle:ModExercice')
						     // ->find($id_exercice);
		
		// if (empty($mod_exercice)) {
			// throw new \Exception('L\'exercice recherché n\'existe pas !');
		// }
		
		// if ($mod_exercice->getIsNew() == true) {
			// $mod_questions = $this->getDoctrine()
								  // ->getManager()
								  // ->getRepository('MajordeskAppBundle:ModQuestion')
								  // ->getOrderedModQuestionsByModExerciceId($id_exercice);
			
			// $mod_briques = $this->getDoctrine()
							    // ->getManager()
							    // ->getRepository('MajordeskAppBundle:ModBrique')
							    // ->getOrderedModBriquesInSuperBriquesByModExercice($id_exercice);
			
			// $mod_briques_c = $this->getDoctrine()
								  // ->getManager()
								  // ->getRepository('MajordeskAppBundle:ModBrique')
								  // ->getOrderedModBriquesInComplementsByModExercice($id_exercice);
			
			// $mod_complements = $this->getDoctrine()
									// ->getManager()
									// ->getRepository('MajordeskAppBundle:ModComplement')
									// ->getOrderedModComplementsByModExercice($id_exercice);
			
			// $mod_mappings = $this->getDoctrine()
								 // ->getManager()
								 // ->getRepository('MajordeskAppBundle:ModMapping')
								 // ->getOrderedModMappingsByModExerciceId($id_exercice);
			
			// $mod_reponses = $this->getDoctrine()
								 // ->getManager()
								 // ->getRepository('MajordeskAppBundle:ModReponse')
								 // ->getOrderedModReponsesByModExercice($id_exercice);

			// return $this->render('MajordeskAppBundle:Eleve:preview-exercice-new.html.twig', array(
				// 'mod_exercice' => $mod_exercice,
				// 'mod_questions' => $mod_questions,
				// 'mod_briques' => $mod_briques,
				// 'mod_briques_c' => $mod_briques_c,
				// 'mod_mappings' => $mod_mappings,
				// 'mod_reponses' => $mod_reponses,
				// 'mod_complements' => $mod_complements,
			// ));
		// }
		// else {
			// $mod_macros_exercice = $this->getDoctrine()
										// ->getManager()
										// ->getRepository('MajordeskAppBundle:ModMacro')
										// ->getOrderedModMacrosExerciceByModExerciceId($id_exercice);
			
			// $mod_elements_exercice = $this->getDoctrine()
										  // ->getManager()
										  // ->getRepository('MajordeskAppBundle:ModElement')
										  // ->getOrderedModElementsExerciceByModExerciceId($id_exercice);
			
			// $mod_questions = $this->getDoctrine()
								  // ->getManager()
								  // ->getRepository('MajordeskAppBundle:ModQuestion')
								  // ->getOrderedModQuestionsByModExerciceId($id_exercice);
			
			// $mod_macros = $this->getDoctrine()
							   // ->getManager()
							   // ->getRepository('MajordeskAppBundle:ModMacro')
							   // ->getOrderedModMacrosByModExerciceId($id_exercice);
			
			// $mod_elements = $this->getDoctrine()
								 // ->getManager()
								 // ->getRepository('MajordeskAppBundle:ModElement')
								 // ->getOrderedModElementsByModExerciceId($id_exercice);
			
			
		
			// return $this->render('MajordeskAppBundle:Eleve:preview-exercice.html.twig', array(
				// 'mod_exercice' => $mod_exercice,
				// 'mod_macros_exercice' => $mod_macros_exercice,
				// 'mod_elements_exercice' => $mod_elements_exercice,
				// 'mod_questions' => $mod_questions,
				// 'mod_macros' => $mod_macros,
				// 'mod_elements' => $mod_elements
			// ));
		// }
	// }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function generateExerciceAction($id)
    {
		$eleve = $this->getUser();
		$mod_exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:ModExercice')
							 ->find($id);
	
		$exercice = new Exercice();
		$exercice->setModExercice($mod_exercice);
		$exercice->setEleve($eleve);
		$exercice->setQueue(1);

		$mod_questions = $mod_exercice->getModQuestions();
		foreach( $mod_questions as $mod_question ) {
			if ( $mod_question->getType() == null || $mod_question->getType() == 'question') { // le == null est à supprimer à termes (utile pour les exercices où is_new = 0
				$question = new Question();
				$question->setModQuestion($mod_question);
				$question->setExercice($exercice); // /!\ devrait faire partie de la même action
				$question->setNumero($mod_question->getNumero());
				$exercice->addQuestion($question); // /!\ devrait faire partie de la même action
			}
		}
		
		// if ($selection != 0) {
			// $exercice->setSelection($selection);
		// }
		$em = $this->getDoctrine()->getManager();
		$em->persist($exercice);
		$em->flush();
		
		return $this->redirect($this->generateUrl('majordesk_app_exercice', array('id_exercice' => $exercice->getId())));
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function ungenerateExerciceAction($id)
    {
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Exercice')
						 ->find($id);
		$id_matiere = $exercice->getModExercice()->getMatiere()->getId();

		$em = $this->getDoctrine()->getManager();
		$em->remove($exercice);
		$em->flush();
		
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF')) {
			return $this->redirect($this->generateUrl('majordesk_app_recherche_exercices', array('id_matiere' => $id_matiere)));
		} else if ($this->get('security.context')->isGranted('ROLE_PROF')) {
			return Response();
		}
	}
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function exerciceAction($id_exercice)  // FIXME à renommer viewExercice ou un truc du style
    {
		$exercice = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:Exercice')
				         ->find($id_exercice);
		
		if (empty($exercice)) {
			throw new \Exception('L\'exercice recherché n\'existe pas !');
		}
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			if ($exercice->getEleve()->getId() != $user->getId()) {
				throw new AccessDeniedException();
			}
			// $exercice->getEleve()->getEleveMatieres()... // FIXME : rajouter une vérification comme quoi cet élève est bien abonné à la plateforme de cette matière, sans quoi il pourrait avoir accès aux exercices en tapant (ou en suivant) une url
		}
		
		if ($exercice->getFavoris()) {
			$today = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$exercice->setDateQueue($today);
			$em = $this->getDoctrine()->getManager();
			$em->persist($exercice);
			$em->flush();
		} 
		
        $mod_exercice = $exercice->getModExercice();
		
		$statut_en_ligne = $this->container->getParameter('statut_en_ligne');
		if ($mod_exercice->getStatut() != $statut_en_ligne) {
			throw new \Exception('L\'exercice recherché est en maintenance et sera disponible prochainement !');
		}
		
		$id = $mod_exercice->getId();
		
		if ($mod_exercice->getIsNew()) {
			// FAIRE UN REDIRECT VERS UNE VUE COMMUNE AVEC L'ELEVE ?
			$mod_questions = $this->getDoctrine()
								  ->getManager()
								  ->getRepository('MajordeskAppBundle:ModQuestion')
								  ->getOrderedModQuestionsByModExerciceId($id);
			
			$mod_briques = $this->getDoctrine()
							    ->getManager()
							    ->getRepository('MajordeskAppBundle:ModBrique')
							    ->getOrderedModBriquesInSuperBriquesByModExercice($id);
			$i=1;
			foreach($mod_briques as $mod_brique) {
				if ($mod_brique == null) { throw new \Exception('i vaut : '.$i.' '.var_dump($mod_briques)); }
				$i++;
			}
			
			return $this->render('MajordeskAppBundle:Eleve:eleve-exercices-new.html.twig', array(
				'mod_exercice' => $mod_exercice,
				'mod_questions' => $mod_questions,
				'mod_briques' => $mod_briques,
				'exercice' => $exercice,
				'id_matiere' => $mod_exercice->getMatiere()->getId()
			));
		}
		else {
			$mod_macros_exercice = $this->getDoctrine()
										->getManager()
										->getRepository('MajordeskAppBundle:ModMacro')
										->getOrderedModMacrosExerciceByModExerciceId($id);
			
			$mod_elements_exercice = $this->getDoctrine()
										  ->getManager()
										  ->getRepository('MajordeskAppBundle:ModElement')
										  ->getOrderedModElementsExerciceByModExerciceId($id);
			
			$mod_questions = $this->getDoctrine()
								  ->getManager()
								  ->getRepository('MajordeskAppBundle:ModQuestion')
								  ->getOrderedModQuestionsByModExerciceId($id);
			
			$mod_macros = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:ModMacro')
							   ->getOrderedModMacrosByModExerciceId($id);
			
			$mod_elements = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:ModElement')
								 ->getOrderedModElementsByModExerciceId($id);
		
			return $this->render('MajordeskAppBundle:Eleve:eleve-exercices.html.twig', array(
				'mod_exercice' => $mod_exercice,
				'mod_macros_exercice' => $mod_macros_exercice,
				'mod_elements_exercice' => $mod_elements_exercice,
				'mod_questions' => $mod_questions,
				'mod_macros' => $mod_macros,
				'mod_elements' => $mod_elements,
				'exercice' => $exercice,
				'id_matiere' => $mod_exercice->getMatiere()->getId()
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function selectRandomInPartieAction($id_partie)
    {
		$ai = $this->get('majordesk_app.service.ai');
		$user = $this->getUser()->getId();	
						
		$exercice = $ai->createRandomInPartie($user, $id_partie);
		$session = $this->get('session');
		
		if ( $exercice == null ) {
			$session->set('partie_only',0);
			$this->get('session')->getFlashBag()->add('info', 'Bravo, tu as terminé tous les exercices de cette partie !');
			$partie = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:Partie')
				           ->find($id_partie);
			return $this->redirect($this->generateUrl('majordesk_app_parties', array('id_chapitre' => $partie->getChapitre()->getId())));
		}
		else {
			$session = $this->get('session');
			$session->set('partie_only',1);
			return $this->redirect($this->generateUrl('majordesk_app_exercice', array('id_exercice' => $exercice->getId())));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function selectNextInQueueAction($id_matiere, $id_chapitre = 0)
    {
		$en_ligne = $this->container->getParameter('statut_en_ligne');
		$ai = $this->get('majordesk_app.service.ai');
		$user = $this->getUser()->getId();	
		$session = $this->get('session');
		if ($session->get('partie_only') == 1) {
			$exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Exercice')
							 ->getLastExerciceByMatiere($user, $id_matiere);
			$id_partie = $exercice->getModExercice()->getPartie()->getId();
			
			$exercice = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:Exercice')
							 ->getNextExerciceInQueueByPartie($user, $id_partie, $en_ligne);
							 
			if ( $exercice == null ) {
				$exercice = $ai->addFromSelectedInChapitreToQueue($user, $id_chapitre);		
			}
			
			if ( $exercice == null ) {
				
				return $this->redirect($this->generateUrl('majordesk_app_exercice_aleatoire_partie', array('id_partie' => $id_partie)));
			}
		}
		if ( empty($exercice) ) {
			if ($session->get('chapitre_only') == 1) {
				$exercice = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:Exercice')
								 ->getLastExerciceByMatiere($user, $id_matiere);
				if ($id_chapitre == 0) {
					$id_chapitre = $exercice->getModExercice()->getChapitre()->getId();
				}
			}
			if ($id_chapitre != 0) {
				$exercice = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:Exercice')
								 ->getNextExerciceInQueueByChapitre($user, $id_chapitre, $en_ligne);
								 
				if ( $exercice == null ) {
					$exercice = $ai->addFromSelectedInChapitreToQueue($user, $id_chapitre);		
				}
				
				if ( $exercice == null ) {
					$exercice = $ai->addRandomInChapitreByMatiereToQueue($user, $id_chapitre);		
				}
			}
			else {
				$exercice = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MajordeskAppBundle:Exercice')
								 ->getNextExerciceInQueueByMatiere($user, $id_matiere, $en_ligne);
								 
				if ( $exercice == null ) {
					$exercice = $ai->addFromSelectedToQueue($user, $id_matiere);
				}
				if ( $exercice == null ) {
					$exercice = $ai->addRandomByMatiereToQueue($user, $id_matiere);	
				}
				if ( $exercice == null ) {
					return $this->redirect($this->generateUrl('majordesk_app_chapitre_selection_queue', array('id_matiere' => $id_matiere)));
				}	
			}
		}
		
		if ( empty($exercice) ) {
			$this->get('session')->getFlashBag()->add('info', 'Bravo, tu as terminé tous les exercices de ce chapitre !');
			return $this->redirect($this->generateUrl('majordesk_app_chapitre_selection_queue', array('id_matiere' => $id_matiere)));
		}
		return $this->redirect($this->generateUrl('majordesk_app_exercice', array('id_exercice' => $exercice->getId())));
    }
	
/* COURS */

	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function verificationDevoirsAction()//$id_matiere)
    {
		$session = $this->get('session');
		$matiere_cours = $session->get('matiere_cours');
		$etape_cours = $session->get('etape_cours');
		if ( $etape_cours == 1 ) {
			$user = $this->getUser();
						  
			$exercices = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:Exercice')
							  ->getExercicesInMatiereSinceLastCours($user->getId(), $matiere_cours); //Since last cours : faire en fonction des tickets, pour l'instant, montre tout
			
			$temps_total = new \DateTime("00:00:00");
			$heures = 0;
			$minutes = 0;
			$secondes = 0;
			foreach($exercices as $exercice) {
				$temps = $exercice->getTemps();		
				$heures += intval($temps->format('G'));
				$minutes += intval($temps->format('i'));
				$secondes += intval($temps->format('s'));
			}
			$temps_total->setTime($heures, $minutes, $secondes);
			
			return $this->render('MajordeskAppBundle:Professeur:gestion-devoirs.html.twig', array(
				'exercices' => $exercices,
				'temps_total' => $temps_total,
			));
		}
		else {
			throw new \Exception('Tu dois être en cours pour accéder à cette section.');
		}
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function donnerDevoirsAction()
    {
		$session = $this->get('session');
		$matiere_cours = $session->get('matiere_cours');
		$etape_cours = $session->get('etape_cours');
		if ( $etape_cours == 3 ) {
			$session->set('etape_cours', 4);
			$etape_cours = 4;
		}
		if ( $etape_cours == 4 ) {
			
			$programmes = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:Programme')
							   ->findAll();
			
			$chapitres = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:Chapitre')
							   ->getChapitresByMatiere($matiere_cours);
			
			$parties = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:Partie')
							   ->getPartiesByMatiere($matiere_cours);				   
			
			return $this->render('MajordeskAppBundle:Professeur:donner-devoirs.html.twig', array(
				'programmes' => $programmes,
				'chapitres' => $chapitres,
				'parties' => $parties
			));
		}
		else {
			throw new \Exception('Une étape semble avoir été sautée...');
		}
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function declarerCoursAction($id_matiere)
    {
		$ai = $this->get('majordesk_app.service.ai');
		$user = $this->getUser();
		$session = $this->get('session');
		// $matiere_cours = $session->get('matiere_cours');
		$etape_cours = $session->get('etape_cours');	
		$debut_cours = $session->get('debut_cours');	
		
		if (empty($etape_cours)) {
			$heure_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$heure_from->sub(new \DateInterval('PT5H'));
			$heure_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$heure_to->add(new \DateInterval('PT1H'));
			
			$cal_events_now = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:CalEvent')
								   ->getEleveCalEvents($user->getId(), $heure_from, $heure_to);
			if (count($cal_events_now) == 0){
				$no_calevent_message = 1;
			}
			else {
				$no_calevent_message = 0;
			}
		}
		else {
			$no_calevent_message = 0;
		}
		
		$professeur_cours = $session->get('professeur_cours');	
		if (empty($professeur_cours)) {
			$professeur = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:Professeur')
							   ->getProfesseurByEleveAndMatiere($user->getId(), $id_matiere);
			$professeur_cours = $professeur->getId();
		}
		if (empty($professeur_cours)) {
			$professeur_cours = $form->getData()->getCalEvent()->getProfesseur->getId();
		}
		if (empty($professeur_cours)) {
			throw new \Exception('Professeur non identifié.');
		}
		
		$famille = $user->getFamille();
		$filtre = $famille->getFiltre();
		$heuresRestantes = $famille->getHeuresRestantes();
		
		$eleve_matiere = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:EleveMatiere')
							  ->getEleveMatiereByEleveAndMatiere($user->getId(), $id_matiere);
		if (empty($eleve_matiere)) {
			throw new \Exception('eleve_matiere ne devrait pas être null');
		}
		$prelevementCours = $eleve_matiere->getPrelevementCours();
		
		if ( $prelevementCours == 1 ) {
			$heures = array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h', '40' => '4h', '50' => '5h');
		}
		else {
			if ($heuresRestantes >= 50) {
				$heures = array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h', '40' => '4h', '50' => '5h');
			}
			else if ($heuresRestantes >= 40) {
				$heures = array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h', '40' => '4h');
			}
			else if ($heuresRestantes >= 30) {
				$heures = array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30', '30' => '3h');
			}
			else if ($heuresRestantes >= 25) {
				$heures = array('10' => '1h', '15' => '1h30', '20' => '2h', '25' => '2h30');
			}
			else if ($heuresRestantes >= 20) {
				$heures = array('10' => '1h', '15' => '1h30', '20' => '2h');
			}
			else if ($heuresRestantes >= 15) {
				$heures = array('10' => '1h', '15' => '1h30');
			}
			else if ($heuresRestantes >= 10) {
				$heures = array('10' => '1h');
			}
			else {
				$heures = array();
			}
		}
		
		$ticket = new Ticket();
		if (!empty($duree_cours)) {
			$duree_cours = time() - $debut_cours;
			if ($duree_cours >= 17000) {
				for($i=5;$i>=1;$i--) {
					$quantite = $i * 10;
					if (array_key_exists( $quantite, $heures )){
						$ticket->setQuantite($quantite);
						break;
					}
				}
			}
			else if ($duree_cours >= 13400) {
				for($i=4;$i>=1;$i--) {
					$quantite = $i * 10;
					if (array_key_exists( $quantite, $heures )){
						$ticket->setQuantite($quantite);
						break;
					}
				}
			}
			else if ($duree_cours >= 9900) {
				for($i=3;$i>=1;$i--) {
					$quantite = $i * 10;
					if (array_key_exists( $quantite, $heures )){
						$ticket->setQuantite($quantite);
						break;
					}
				}
			}
			else if ($duree_cours >= 8100) {
				if (array_key_exists( 25, $heures )){
					$ticket->setQuantite(25);
				}
			}
			else if ($duree_cours >= 6300) {
				if (array_key_exists( 20, $heures )){
					$ticket->setQuantite(20);
				}
			}
			else if ($duree_cours >= 4500) {
				if (array_key_exists( 15, $heures )){
					$ticket->setQuantite(15);
				}
			}
			else {
				if (array_key_exists( 10, $heures )){
					$ticket->setQuantite(10);
				}
			}
		}
		if (empty($professeur)) {
			$professeur = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MajordeskAppBundle:Professeur')
							   ->find($professeur_cours);
		}
		$ticket->setProfesseur($professeur);
		$ticket->setEleve($user);
		
		if ($filtre) {
			$form = $this->createForm( new TicketEnfantType($heures, $user->getId()), $ticket );
		} else {
			$form = $this->createForm( new TicketEnfantNoFiltreType($heures, $user->getId()), $ticket );
		}
			
		$request = $this->getRequest();
		
		if ( $etape_cours == 4 ) {
			$session->set('etape_cours', 5);

			if ($request->getMethod() == 'POST') 
			{
				$em = $this->getDoctrine()->getManager();
				for ($i=1;$i<=12;$i++) {
					$id_chapitre = $request->request->get('chapitre_'.$i, null);
					if ($id_chapitre != null) {
						$nombre_chapitre = $request->request->get('chapitre_nb_'.$i);
						$selection = -1*intval($nombre_chapitre);
						$exercice = $ai->addRandomInChapitreByMatiereToQueue($user->getId(), $id_chapitre, $selection);	
					}
					else {
						break;
					}
				}
				for ($i=1;$i<=12;$i++) {
					$id_partie = $request->request->get('partie_'.$i, null);
					if ($id_partie != null) {
						$nombre_partie = $request->request->get('partie_nb_'.$i);
						$selection = intval($nombre_partie);
						$exercice = $ai->createRandomInPartie($user->getId(), $id_partie, $selection);	
					}
					else {
						break;
					}
				}
				$session->getFlashBag()->add('info', 'Les devoirs ont été donnés avec succès !');
			}
		}
		else {
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{				
					$paymentAuthorized = false;
					if ($filtre) {
						$passparent = $form->get('passparent')->getData();
						$factory = $this->get('security.encoder_factory');
						$parents = $famille->getClients();
						foreach($parents as $parent) {
							$encoder = $factory->getEncoder($parent);			
							$encoded_pass = $encoder->encodePassword($passparent, $parent->getSalt());

							if ($encoded_pass == $parent->getPassword()) {
								$paymentAuthorized = true;
								break;
							}
						}
					}
					$quantite = $ticket->getQuantite();
					if ($quantite == 10) {
						$temps = '1h';
					} else if ($quantite == 15) {
						$temps = '1h30';
					} else if ($quantite == 20) {
						$temps = '2h';
					} else if ($quantite == 25) {
						$temps = '2h30';
					} else if ($quantite == 30) {
						$temps = '3h';
					} else if ($quantite == 40) {
						$temps = '4h';
					} else if ($quantite == 50) {
						$temps = '5h';
					} else {
						$temps = 'n.c.';
					}
					$heuresReellesRestantes = $heuresRestantes / 10;
					$heuresIncrementees = $heuresReellesRestantes - $quantite / 10;
					$em = $this->getDoctrine()->getManager();
					
					if ($paymentAuthorized || $filtre == false) {
						$professeur->setHeuresDonnees($professeur->getHeuresDonnees() + $quantite);
						if ($quantite <= $heuresRestantes) {
							$ticket->setRegle(false);
							$paiement = new Paiement();
							$paiement->setDescription($user->getUsername().' a pris un cours de '.$temps.' : <em>'.$ticket->getCalEvent()->getTitre().'</em><br>Il vous reste <strong>'.$heuresIncrementees.'</strong> heure(s) de cours.');
							$paiement->setFamille($famille);
							$paiement->setPack('1'.$quantite);
							$paiement->setMontant(0);
							$paiement->setTransaction(2);  // 0: annulé, 1: en cours, 2: validé, 3:ticket non réglé
							$paiement->setTicket($ticket);
							
							$famille->setHeuresRestantes($heuresRestantes - $quantite);
							$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
							$user->setHeuresPrises($user->getHeuresPrises() + $quantite);
							$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
							
							$em->persist($eleve_matiere);
							$em->persist($paiement);
							$em->persist($famille);
						}
						else if ($prelevementCours == 1) {
							if ($heuresRestantes > 0) {
								$ticket->setRegle(false);
								
								$paiement = new Paiement();
								$paiement->setDescription($user->getUsername().' a pris un cours de '.$temps.' : <em>'.$ticket->getCalEvent()->getTitre().'</em><br>Il vous restait '.$heuresReellesRestantes.' heure(s) qui sont maintenant épuisée(s).<br>Le complément de paiement va être effectué avec votre numéro d\'abonné.');
								$paiement->setFamille($famille);
								$paiement->setPack('1'.$heuresRestantes);
								$paiement->setMontant(0);
								$paiement->setTransaction(2);
								$paiement->setTicket($ticket);
								
								$famille->setHeuresRestantes(0);
								
								$new_quantite = $quantite - $heuresRestantes;

								$paiement2 = new Paiement();
								$paiement2->setDescription('Ceci est le complément de paiement pour le cours : <em>'.$ticket->getCalEvent()->getTitre().'</em>');
								$paiement2->setFamille($famille);
								$paiement2->setPack('2'.$new_quantite);
								$paiement2->setMontant(599.0*$new_quantite);
								$paiement2->setTransaction(1);
								$paiement2->setTicket($ticket);
								
								$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
								$user->setHeuresPrises($user->getHeuresPrises() + $quantite);
								$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
								
								$em->persist($eleve_matiere);
								$em->persist($paiement);
								$em->persist($paiement2);
								$em->persist($famille);
							}
							else {
								$ticket->setRegle(false);
							
								$paiement = new Paiement();
								$paiement->setDescription($user->getUsername().' a pris un cours de '.$temps.' : <em>'.$ticket->getCalEvent()->getTitre().'</em>');
								$paiement->setFamille($famille);
								$paiement->setPack('2'.$quantite);
								$paiement->setMontant(599.0*$quantite);
								$paiement->setTransaction(1);
								$paiement->setTicket($ticket);
								
								$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
								$user->setHeuresPrises($user->getHeuresPrises() + $quantite);
								$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
								
								$em->persist($eleve_matiere);
								$em->persist($paiement);
								$em->persist($famille);
							}
						}
						else {
							$ticket->setRegle(false);
							
							$paiement = new Paiement();
							$paiement->setDescription($user->getUsername().' a pris un cours de '.$temps.' : <em>'.$ticket->getCalEvent()->getTitre().'</em><br>Il ne vous reste pas suffisamment d\'heures pour payer ce cours.<br><strong>Veuillez recréditer votre compte.</strong>');
							$paiement->setFamille($famille);
							$paiement->setPack('3'.$quantite);
							$paiement->setMontant(599.0*$quantite);
							$paiement->setTransaction(3);
							$paiement->setTicket($ticket);
							
							$famille->setHeuresRestantes($heuresRestantes - $quantite);
							$famille->setHeuresPrises($famille->getHeuresPrises() + $quantite);
							$user->setHeuresPrises($user->getHeuresPrises() + $quantite);
							$eleve_matiere->setHeuresPrises($eleve_matiere->getHeuresPrises() + $quantite);
							
							$em->persist($eleve_matiere);
							$em->persist($paiement);
							$em->persist($famille);
						}
						
						$em->persist($ticket);
						$em->persist($professeur);
						$em->flush();
						
						$session->getFlashBag()->add('info', ' Le cours s\'est terminé avec succès !');

						$session->remove('matiere_cours');
						$session->remove('type_cours');
						$session->remove('etape_cours');
						$session->remove('debut_cours');
						$session->remove('professeur_cours');
						return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
					}
					else {
						$session->getFlashBag()->add('warning', ' Mot de passe du parent incorrect.');
					}
				}
			}
		}
		
		return $this->render('MajordeskAppBundle:Professeur:declarer-cours.html.twig', array(
			'form' => $form->createView(),
			'id_matiere' => $id_matiere,
			'no_calevent_message' => $no_calevent_message,
			'heuresRestantes' => $heuresRestantes,
			'prelevementCours' => $prelevementCours,
			'filtre' => $filtre,
		));
    }

/* CALENDRIER */
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function calendrierAction($etape = 0)
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_PARENTS')) {	
		
			$session = $this->get('session');
			$etape_cours = $session->get('etape_cours');
			if ($etape == 3 && $etape_cours == 2) {
				$session->set('etape_cours', 3);
			}
			
			$user = $this->getUser();
			$professeurs = $user->getProfesseurs();
			
			$cours = new CalEvent();
			$cours->setEleve($user);
			
			// $errors ='';
			$form = $this->createForm(new CoursType($user->getId()), $cours);
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{
					date_default_timezone_set("Europe/Paris"); 
					if (strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') > time() - 2 * 3600 ) {
						
						$matiere = $form->getData()->getMatiere();
						$id_matiere = $matiere->getId();
						
						$professeur = $this->getDoctrine()
										   ->getManager()
										   ->getRepository('MajordeskAppBundle:Professeur')
										   ->getProfesseurByEleveAndMatiere($user->getId(), $id_matiere);
						
						if ($professeur !== null) {		
							
							$cal_events = $this->getDoctrine()
											   ->getManager()
											   ->getRepository('MajordeskAppBundle:CalEvent')
											   ->getAllProfesseurCalEvents($professeur->getId());
							$authorized = true;
							foreach($cal_events as $cal_event) {
								if ( strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') < strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00') && strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureFin().':00') > strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00') ) {
									$authorized = false;
									break;
								}
							}
							
							if ($authorized) {
								$cours->setProfesseur($professeur);
								$cours->setTitre('Cours avec '.$professeur->getUsername().' ('.$form->getData()->getHeureDebut().'-'.$form->getData()->getHeureFin().')');
								
								$em = $this->getDoctrine()->getManager();
								$em->persist($cours);
								$em->flush();
								$this->get('session')->getFlashBag()->add('info', ' Cours programmé. Une demande de confirmation a été envoyée à ton professeur.');
							}
							else {
								$this->get('session')->getFlashBag()->add('warning', ' Ton professeur est déjà pris par un cours sur ce créneau.');
							}
						}
						else {
							$this->get('session')->getFlashBag()->add('warning', ' Aucun professeur ne t\'as encore été attribué dans cette matière.');
						}
					}
					else {
						$this->get('session')->getFlashBag()->add('warning', ' Impossible de demander un cours à une date passée de plus de 2 heures!');
					}
				}
				else {
					// $errors = $form->getErrorsAsString();
					$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
				}
			}
			return $this->render('MajordeskAppBundle:Eleve:calendrier.html.twig', array(
				'form' => $form->createView(),
				'professeurs' => $professeurs,
				// 'errors' => $errors
			));
		}
		else if ($this->get('security.context')->isGranted('ROLE_PARENTS')) {
			$user = $this->getUser();
			$famille = $user->getFamille();
			$eleves = $famille->getEleves();
			
			$professeurs = $famille->getProfesseurs();
			
			$cours = new CalEvent();
			if (count($eleves) > 1) {
				// $errors ='';
				$form = $this->createForm(new CoursFilterType($famille->getId()), $cours);
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{
						date_default_timezone_set("Europe/Paris"); 
						if (strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') > time() ) {
							
							$matiere = $form->getData()->getMatiere();
							$eleve = $form->getData()->getEleve();
							$id_matiere = $matiere->getId();
							
							$professeur = $this->getDoctrine()
											   ->getManager()
											   ->getRepository('MajordeskAppBundle:Professeur')
											   ->getProfesseurByEleveAndMatiere($eleve->getId(), $id_matiere);
							
							if ($professeur !== null) {		
								
								$cal_events = $this->getDoctrine()
												   ->getManager()
												   ->getRepository('MajordeskAppBundle:CalEvent')
												   ->getAllProfesseurCalEvents($professeur->getId());
								$authorized = true;
								foreach($cal_events as $cal_event) {
									if ( strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') < strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00') && strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureFin().':00') > strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00') ) {
										$authorized = false;
										break;
									}
								}
								
								if ($authorized) {
									$cours->setProfesseur($professeur);
									$cours->setTitre('Cours avec '.$professeur->getUsername().' ('.$form->getData()->getHeureDebut().'-'.$form->getData()->getHeureFin().')');
									
									$em = $this->getDoctrine()->getManager();
									$em->persist($cours);
									$em->flush();
									$this->get('session')->getFlashBag()->add('info', ' Cours programmé. Une demande de confirmation a été envoyée au professeur.');
								}
								else {
									$this->get('session')->getFlashBag()->add('warning', ' Le professeur assigné est déjà pris par un cours sur ce créneau.');
								}
							}
							else {
								$this->get('session')->getFlashBag()->add('warning', ' Aucun professeur n\'a encore été attribué dans cette matière.');
							}
						}
						else {
							$this->get('session')->getFlashBag()->add('warning', ' Impossible de demander un cours à une date passée!');
						}
					}
					else {
						// $errors = $form->getErrorsAsString();
						$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
					}
				}
			}
			else {
				$cours->setEleve($eleves[0]);
				
				// $errors ='';
				$form = $this->createForm(new CoursType($eleves[0]->getId()), $cours);
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{
						date_default_timezone_set("Europe/Paris"); 
						if (strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') > time() ) {
							
							$matiere = $form->getData()->getMatiere();
							$id_matiere = $matiere->getId();
							
							$professeur = $this->getDoctrine()
											   ->getManager()
											   ->getRepository('MajordeskAppBundle:Professeur')
											   ->getProfesseurByEleveAndMatiere($eleves[0]->getId(), $id_matiere);
							
							if ($professeur !== null) {		
								
								$cal_events = $this->getDoctrine()
												   ->getManager()
												   ->getRepository('MajordeskAppBundle:CalEvent')
												   ->getAllProfesseurCalEvents($professeur->getId());
								$authorized = true;
								foreach($cal_events as $cal_event) {
									if ( strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureDebut().':00') < strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureFin().':00') && strtotime($form->getData()->getDateCours()->format('Y-m-d').' '.$form->getData()->getHeureFin().':00') > strtotime($cal_event->getDateCours()->format('Y-m-d').' '.$cal_event->getHeureDebut().':00') ) {
										$authorized = false;
										break;
									}
								}
								
								if ($authorized) {
									$cours->setProfesseur($professeur);
									$cours->setTitre('Cours avec '.$professeur->getUsername().' ('.$form->getData()->getHeureDebut().'-'.$form->getData()->getHeureFin().')');
									
									$em = $this->getDoctrine()->getManager();
									$em->persist($cours);
									$em->flush();
									$this->get('session')->getFlashBag()->add('info', ' Cours programmé. Une demande de confirmation a été envoyée au professeur.');
								}
								else {
									$this->get('session')->getFlashBag()->add('warning', ' Le professeur assigné est déjà pris par un cours sur ce créneau.');
								}
							}
							else {
								$this->get('session')->getFlashBag()->add('warning', ' Aucun professeur n\'a encore été attribué dans cette matière.');
							}
						}
						else {
							$this->get('session')->getFlashBag()->add('warning', ' Impossible de demander un cours à une date passée!');
						}
					}
					else {
						// $errors = $form->getErrorsAsString();
						$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
					}
				}	
			}
			return $this->render('MajordeskAppBundle:Eleve:calendrier.html.twig', array(
				'form' => $form->createView(),
				'professeurs' => $professeurs,
				'eleves' => $eleves,
				// 'errors' => $errors
			));
		}
		return $this->render('MajordeskAppBundle:Eleve:calendrier.html.twig');
    }
	
	/**
	 * @Secure(roles="ROLE_ELEVE")
	 */
    public function cancelEventAction($id_event)
    {
		if ($this->get('security.context')->isGranted('ROLE_ELEVE') && !$this->get('security.context')->isGranted('ROLE_PROF')) {	
			$calEvent = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MajordeskAppBundle:CalEvent')
							 ->find($id_event);

			$dateCours = $calEvent->getDateCours();		
			$heureDebut = $calEvent->getHeureDebut();	
			date_default_timezone_set("Europe/Paris"); 			
			if (time() < strtotime($dateCours->format('Y-m-d').' '.$heureDebut.':00') - 5*3600) {		
				$calEvent->setReservation(0);
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($calEvent);
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('info', 'Le cours a bien été annulé.');
				return $this->redirect($this->generateUrl('majordesk_app_calendrier_des_cours'));
			}
			else {
				$this->get('session')->getFlashBag()->add('warning', 'Ce cours n\'a pas pu être annulé, car le délai minimum des 5h avant le cours a été dépassé.');
				return $this->redirect($this->generateUrl('majordesk_app_calendrier_des_cours'));
			}
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PROF")
	 */
    public function profEventAction($id_event, $reservation)
    {
		$calEvent = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MajordeskAppBundle:CalEvent')
						 ->find($id_event);
		
		$calEvent->setReservation($reservation);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($calEvent);
		$em->flush();

		$this->get('session')->getFlashBag()->add('info', 'Cours confirmé.');
		return $this->redirect($this->generateUrl('majordesk_app_calendrier_des_cours'));
    }
}
