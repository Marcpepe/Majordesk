<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Majordesk\AppBundle\Form\Type\InscriptionEleveType;

use Majordesk\AppBundle\Entity\Paiement;
use Majordesk\AppBundle\Entity\Eleve;
use Majordesk\AppBundle\Entity\EleveMatiere;
use Majordesk\AppBundle\Entity\Disponibilite;
use Majordesk\AppBundle\Form\Type\CoursType;
use Majordesk\AppBundle\Form\Type\CoursFilterType;
use Majordesk\AppBundle\Form\Type\TicketNoFiltreType;
use Majordesk\AppBundle\Form\Type\TicketSelectNoFiltreType;
use Majordesk\AppBundle\Entity\CalEvent;
use Majordesk\AppBundle\Entity\Ticket;

class ParentsController extends Controller
{
    /**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function indexParentsAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			$famille = $user->getFamille();
			$eleves = $famille->getEleves();
			$heuresRestantes = $famille->getHeuresRestantes();			
			// $professeurs = $famille->getProfesseurs();
			
			$filtre = $famille->getFiltre();
			
			// $cours = new CalEvent();
			if (count($eleves) > 1) {
				// $errors ='';
				
				$ticket = new Ticket();
				
				// $ticket->setEleve($user);
				$form = $this->createForm( new TicketSelectNoFiltreType($famille->getId()), $ticket );
				
				// Pour enfant
				// if ($filtre) {
					// $form = $this->createForm( new TicketEnfantType($heures, $user->getId()), $ticket );
				// } else {
					// $form = $this->createForm( new TicketEnfantNoFiltreType($heures, $user->getId()), $ticket );
				// }
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{
						
						// Pour enfant
						// $paymentAuthorized = false;
						// if ($filtre) {
							// $passparent = $form->get('passparent')->getData();
							// $factory = $this->get('security.encoder_factory');
							// $parents = $famille->getClients();
							// foreach($parents as $parent) {
								// $encoder = $factory->getEncoder($parent);			
								// $encoded_pass = $encoder->encodePassword($passparent, $parent->getSalt());

								// if ($encoded_pass == $parent->getPassword()) {
									// $paymentAuthorized = true;
									// break;
								// }
							// }
						// }
						
						$eleve = $ticket->getEleve();						
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
						
						// if ($paymentAuthorized || $filtre == false) {
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
							$this->get('session')->getFlashBag()->add('info', ' Le cours a été déclaré avec succès !');

							// $session->remove('matiere_cours');
							// $session->remove('type_cours');
							// $session->remove('etape_cours');
							// $session->remove('debut_cours');
							// $session->remove('professeur_cours');
							// return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
						// }
						// else {
							// $session->getFlashBag()->add('warning', ' Mot de passe du parent incorrect.');
						// }
					
						// $em = $this->getDoctrine()->getManager();
						// $em->persist($ticket);
						// $em->flush();
						// $this->get('session')->getFlashBag()->add('info', ' Cours programmé. Une demande de confirmation a été envoyée au professeur.');
					}
					else {
						// $errors = $form->getErrorsAsString();
						$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
					}
				}
			}
			else {
				$eleve = $eleves[0];
				
				$ticket = new Ticket();
				
				$ticket->setEleve($eleve);
				$form = $this->createForm( new TicketNoFiltreType($eleve->getId()), $ticket );
				
				// Pour enfant
				// if ($filtre) {
					// $form = $this->createForm( new TicketEnfantType($heures, $user->getId()), $ticket );
				// } else {
					// $form = $this->createForm( new TicketEnfantNoFiltreType($heures, $user->getId()), $ticket );
				// }
				
				$request = $this->getRequest();
				if ($request->getMethod() == 'POST') 
				{
					$form->bind($request);

					if ($form->isValid()) 
					{
						
						// Pour enfant
						// $paymentAuthorized = false;
						// if ($filtre) {
							// $passparent = $form->get('passparent')->getData();
							// $factory = $this->get('security.encoder_factory');
							// $parents = $famille->getClients();
							// foreach($parents as $parent) {
								// $encoder = $factory->getEncoder($parent);			
								// $encoded_pass = $encoder->encodePassword($passparent, $parent->getSalt());

								// if ($encoded_pass == $parent->getPassword()) {
									// $paymentAuthorized = true;
									// break;
								// }
							// }
						// }
						
						// $eleve = $ticket->getEleve();						
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
						
						// if ($paymentAuthorized || $filtre == false) {
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
							$this->get('session')->getFlashBag()->add('info', ' Le cours a été déclaré avec succès !');

							// $session->remove('matiere_cours');
							// $session->remove('type_cours');
							// $session->remove('etape_cours');
							// $session->remove('debut_cours');
							// $session->remove('professeur_cours');
							// return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
						// }
						// else {
							// $session->getFlashBag()->add('warning', ' Mot de passe du parent incorrect.');
						// }
					
						// $em = $this->getDoctrine()->getManager();
						// $em->persist($ticket);
						// $em->flush();
						// $this->get('session')->getFlashBag()->add('info', ' Cours programmé. Une demande de confirmation a été envoyée au professeur.');
					}
					else {
						// $errors = $form->getErrorsAsString();
						$this->get('session')->getFlashBag()->add('warning', ' Un ou plusieurs champs ont été mal remplis.');
					}
				}
			}
			
			$paiements = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:Paiement')
							  ->getDescPaiementsLimit($famille->getId(), 3);
			
			// $date_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			// $date_from->sub(new \DateInterval('PT8H'));
			// $date_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			// $date_to->add(new \DateInterval('P30D'));
			
			// $cal_events_proches = $this->getDoctrine()
									   // ->getManager()
									   // ->getRepository('MajordeskAppBundle:CalEvent')
									   // ->getFamilleCalEventsProches($user->getFamille()->getId(), $date_from, $date_to);
									   
			return $this->render('MajordeskAppBundle:Parents:index-parents.html.twig', array(
				'form' => $form->createView(),
				'famille' => $famille,
				'eleves' => $eleves,
				'paiements' => $paiements,
				// 'cal_events_proches' => $cal_events_proches,
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function nouvelEleveAction()
    {
		$user = $this->getUser();
		$famille = $user->getFamille();
		
		$eleve = new Eleve();
		$form = $this->createForm(new InscriptionEleveType(), $eleve);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);

			if ($form->isValid()) 
			{															
				if ($form->getData()->getProgramme() != null) {
					$matiere_maths = $request->request->get('matiere_maths');
					$matiere_physique = $request->request->get('matiere_physique');	
					
					if (!empty($matiere_maths) || !empty($matiere_physique)) 
					{		
						$factory = $this->get('security.encoder_factory');
						// $container = $this->getContainer(); 
						
						$eleve->setSalt(time());
							$encoder = $factory->getEncoder($eleve);
							$pass = $encoder->encodePassword($eleve->getPassword(), $eleve->getSalt()); 
						
						$famille->addEleve($eleve);
						$em = $this->getDoctrine()->getManager();
						$em->persist($famille);
						$em->flush();
						
						$dateNotification = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
						$notification = 'Inscription d\un nouvel enfant pour la famille '.$user->getNom().' : '.$eleve->getUsername().' en classe de '.$eleve->getProgramme()->getNom();
						
						$message = \Swift_Message::newInstance()
												->setSubject('Urgent : Erreur Cron')
												->setFrom('plateforme@majorclass.fr')
												->setTo('marc@majorclass.fr')
												// ->setBody($this->get('templating')->render('MajordeskAppBundle:Admin:notification.txt.twig', array('dateNotification' => $dateNotification, 'notification'=>$notification)))
												->setBody($this->renderView('MajordeskAppBundle:Admin:notification.txt.twig', array('dateNotification' => $dateNotification, 'notification'=>$notification)))
											;
											$this->get('mailer')->send($message);
											$transport = $this->get('swiftmailer.transport.real');						
											// $this->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
											$this->get('mailer')->send($message);
						
						return $this->redirect($this->generateUrl('majordesk_app_profil'));
					}	
					else {
						$this->get('session')->getFlashBag()->add('warning-matiere', 'Veuillez sélectionner au moins une matière.');
					}
				}
				else {
					$this->get('session')->getFlashBag()->add('info', 'Programme non renseigné.');
				}
			}
			$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
		}
		
		return $this->render('MajordeskAppBundle:Parents:nouvel-eleve.html.twig', array(
			'form' => $form->createView()
		));
	}
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function suiviEnfantAction($id_eleve)
    {
		if ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			$famille = $user->getFamille();
			$eleves = $famille->getEleves();
			
			$eleve = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('MajordeskAppBundle:Eleve')
					  ->find($id_eleve);
		
		if (empty($eleve)) {
			throw new \Exception('Aucun élève ne correspond à la demande.');
		}
		if (!in_array($eleve, $eleves->toArray())) {
			throw new \Exception('Cet enfant n\'est pas le vôtre !');
		}
					  
		$exercices = $this->getDoctrine()
					      ->getManager()
					      ->getRepository('MajordeskAppBundle:Exercice')
						  ->getExercicesSinceLastCours($id_eleve, $user->getId());
		
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
			'eleves' => $eleves,
			'eleve' => $eleve,
			'exercices' => $exercices,
			'temps_total' => $temps_total,
		));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function abonnementsFacturesAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			$famille = $user->getFamille();
			$eleves = $famille->getEleves();
			$paiements = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:Paiement')
							  ->getDescPaiementsLimit($famille->getId(), 15);
				   
			return $this->render('MajordeskAppBundle:Parents:abonnements-factures.html.twig', array(
				'famille' => $famille,
				'eleves' => $eleves,
				'paiements' => $paiements,
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function enregistrementCarteAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			$famille = $user->getFamille();
			$eleves = $famille->getEleves();
				   
			return $this->render('MajordeskAppBundle:Parents:enregistrement-carte.html.twig', array(
				'famille' => $famille,
				'eleves' => $eleves,
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function gestionAbonnementsAction()
    {
		$user = $this->getUser();
		$famille = $user->getFamille();
		$eleves = $famille->getEleves();
		return $this->render('MajordeskAppBundle:Parents:gestion-abonnements.html.twig', array(
				'famille' => $famille,
				'eleves' => $eleves
			));
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function parametresCompteAction()
    {
		$user = $this->getUser();
		$famille = $user->getFamille();
		$eleves = $famille->getEleves();
		return $this->render('MajordeskAppBundle:Parents:parametres-compte.html.twig', array(
				'famille' => $famille,
				'eleves' => $eleves
			));
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function nouvelAbonnementAction($etape_inscription)
	{
		$session = $this->get('session');
		$em = $this->getDoctrine()->getManager();
		$new_inscription = $session->get('new_inscription');
		if ($etape_inscription == 1) {
			if ($new_inscription == null) {
				$session->set('new_inscription', 1);
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
							
						$session->set('new_inscription', 2);
						return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 2)));
					}
					else {
						$this->get('session')->getFlashBag()->add('info', 'Programme non renseigné.');
					}
				}
				$this->get('session')->getFlashBag()->add('warning', 'Un ou plusieurs champs ont été mal remplis.');
			}
		}
		else if ($etape_inscription == 2) {
		
			if ($new_inscription < 2) {
				return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 1)));
			}
		
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
						
						
						$packs = array(11,21,22,23,24,31,32,33,34);
						if (in_array($pack, $packs)) {
							$session->set('new_inscription', 3);
							return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 3)));
						}
						else {
							$this->get('session')->getFlashBag()->add('warning-pack', 'Une erreur est survenue lors de la sélection du pack.');
							return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 2)));
						}
						
						
					}
					$this->get('session')->getFlashBag()->add('warning-formule', 'Veuillez sélectionner un pack.');
					return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 3)));
				}
				$this->get('session')->getFlashBag()->add('warning-matiere', 'Veuillez sélectionner au moins une matière.');
				return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 3)));
			}
		
			return $this->render('MajordeskAppBundle:Parents:nouvel-abonnement.html.twig', array(
				'etape_inscription' => $etape_inscription,
			));
		}
		else if ($etape_inscription == 3) {
			$user = $this->getUser();
			$user_mail = $user->getMail();
			$famille = $user->getFamille();
			$abonnement = $famille->getAbonnement();
			$pack = $session->get('pack');
			
			if (in_array($pack, array(23,24,33,34))){
				if (!empty($abonnement)) {
					return $this->render('MajordeskAppBundle:Parents:nouvelle-inscription-choix.html.php', array(
						'pack' => $pack,
						'etape_inscription' => $etape_inscription,
						'user_mail' => $user_mail,
					));
				}
				else {
					return $this->render('MajordeskAppBundle:Parents:nouvelle-inscription-paiement.html.php', array(
						'pack' => $pack,
						'etape_inscription' => $etape_inscription,
						'user_mail' => $user_mail,
					));
				}
			}
			else if (in_array($pack, array(11,21,22,31,32))) {
				if (!empty($abonnement)) {
					return $this->render('MajordeskAppBundle:Parents:nouvelle-inscription-done.html.twig', array(
						'pack' => $pack,
						'etape_inscription' => $etape_inscription,
						'user_mail' => $user_mail,
						'abonnement' => $abonnement,
					));
				}
				else {
					return $this->render('MajordeskAppBundle:Parents:nouvelle-inscription-subscription.html.php', array(
						'pack' => $pack,
						'etape_inscription' => $etape_inscription,
						'user_mail' => $user_mail,
					));
				}
			}
			else {
				$this->get('session')->getFlashBag()->add('warning-pack', 'Une erreur est survenue lors de la sélection du pack.');
				return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 2)));
			}
		}
		
		return $this->render('MajordeskAppBundle:Parents:nouvel-abonnement.html.twig', array(
			'etape_inscription' => $etape_inscription,
			'form' => $form->createView()
		));
	}
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function nouvelleInscriptionFinalisationAction()
	{
		$session = $this->get('session');
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$famille = $user->getFamille();
		$pack = $session->get('pack');
		$matieres = explode('##',$session->get('matieres'));
		
		$packs = array(11,21,22,23,24,31,32,33,34);
		if (in_array($pack, $packs) && !empty($matieres)) {
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
				
				/* password encryption --> faire un service */
				$eleve->setSalt(time());
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($eleve);
				$password = $encoder->encodePassword($session->get('password'), $eleve->getSalt()); // $eleve->getPassword()   <=>   $form->get('password')->getData()
				$eleve->setPassword($password);
			}
			else {
				$session->set('new_inscription', 1);
				$this->get('session')->getFlashBag()->add('warning', 'Une erreur est survenue, veuillez recommencer l\'inscription.');
				return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 1)));
			}
			
			if ($pack == 11) {	
				foreach($matieres as $id_matiere) {
					if ($id_matiere == 1) {
						$matiere = $this->getDoctrine()
										->getManager()
										->getRepository('MajordeskAppBundle:Matiere')
										->find($id_matiere);
						$eleve_matiere = new EleveMatiere();
						$eleve_matiere->setPlateforme(1);
						$eleve_matiere->setPrelevementPlateforme(1);
						$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
							$data_abo->add(new \DateInterval('P1M'));
						$eleve_matiere->setDateAbonnement($data_abo);
						$matiere->addEleveMatiere($eleve_matiere);
						$eleve->addEleveMatiere($eleve_matiere);
						$em->persist($matiere);
						$em->persist($eleve_matiere);
					}
				}
			}
			else if ($pack == 21) {	
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
			else if ($pack == 22) {	
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
			}
			else if ($pack == 31) {	
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
			}
			else if ($pack == 32) {	
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
			}
			
			$famille->addEleve($eleve);
			
			$paiement = new Paiement();
			if ($pack == 11) {
				$paiement->setPack(-1*$pack);
				$paiement->setMontant(5990);
				$paiement->setTransaction(1);
				$paiement->setDescription('<em>Nouvel abonnement :</em> '.$eleve->getUsername().' est maintenant abonné(e) à la Plateforme <span class="label label-info">Mathématiques</span><br>Débit du premier mois.');
				$this->get('session')->getFlashBag()->add('info', 'Inscription réussie! Votre enfant peut se connecter dès maintenant.');
			} else if ($pack == 21) {
				$paiement->setPack(-1*$pack);
				$paiement->setMontant(0);
				$paiement->setTransaction(2);
				$paiement->setDescription($eleve->getUsername().' est maintenant inscrit(e) aux cours.<br>Un professeur va bientôt lui être assigné.');
				$this->get('session')->getFlashBag()->add('info', 'Inscription réussie! Un professeur va bientôt être assigné à votre enfant.');
			} else if ($pack == 22) {
				$paiement->setPack(-1*$pack);
				$paiement->setMontant(0);
				$paiement->setTransaction(2);
				$paiement->setDescription($eleve->getUsername().' est maintenant inscrit(e) aux cours.<br>Un professeur va bientôt lui être assigné.');
				$this->get('session')->getFlashBag()->add('info', 'Inscription réussie! Un professeur va bientôt être assigné à votre enfant.');
			} else if ($pack == 31) {
				$paiement->setPack(-1*$pack);
				$paiement->setMontant(5990);
				$paiement->setTransaction(1);
				$paiement->setDescription('<em>Nouvel abonnement :</em> '.$eleve->getUsername().' est maintenant abonné(e) à la Plateforme <span class="label label-info">Mathématiques</span><br>Débit du premier mois.<br>'.$eleve->getUsername().' est inscrit(e) aux cours. Un professeur va bientôt lui être assigné.');
				$this->get('session')->getFlashBag()->add('info', 'Inscription réussie! Un professeur va bientôt être assigné à votre enfant.');
			} else if ($pack == 32) {
				$paiement->setPack(-1*$pack);
				$paiement->setMontant(5990);
				$paiement->setTransaction(1);
				$paiement->setDescription('<em>Nouvel abonnement :</em> '.$eleve->getUsername().' est maintenant abonné(e) à la Plateforme <span class="label label-info">Mathématiques</span><br>Débit du premier mois.<br>'.$eleve->getUsername().' est inscrit(e) aux cours. Un professeur va bientôt lui être assigné.');
				$this->get('session')->getFlashBag()->add('info', 'Inscription réussie! Un professeur va bientôt être assigné à votre enfant.');
			}

			$famille->addPaiement($paiement);

			$em->persist($famille);
			$em->flush();	
						
			$session->remove('prog');
			$session->remove('username');
			$session->remove('nom');
			$session->remove('lycee');
			$session->remove('telephone');
			$session->remove('mail');
			$session->remove('password');
			$session->remove('new_inscription');
			$session->remove('pack');
			$session->remove('matieres');
			for($i=1;$i<=7;$i++) {
				if (!empty(${'jour_'.$i}) || !empty(${'heure_'.$i})) {
					$session->remove('jour_'.$i);
					$session->remove('heure_'.$i);
				}
				else {
					break;
				}
			}			
			
			return $this->redirect($this->generateUrl('majordesk_app_gestion_abonnements'));
		}
		else {
			$this->get('session')->getFlashBag()->add('warning-pack', 'Une erreur est survenue lors de la sélection du pack.');
			return $this->redirect($this->generateUrl('majordesk_app_nouvel_abonnement', array('etape_inscription' => 2)));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function detailsHeuresAction()
    {
		$user = $this->getUser();
		$famille = $user->getFamille();
		$tickets = $this->getDoctrine()
					    ->getManager()
					    ->getRepository('MajordeskAppBundle:Ticket')
					    ->getTicketsByFamille($famille->getId());
			   
		return $this->render('MajordeskAppBundle:Parents:details-heures.html.twig', array(
			'famille' => $famille,
			'tickets' => $tickets,
		));
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function achatHeuresAction()
    { 
		$user = $this->getUser();
		$famille = $user->getFamille();
				
		return $this->render('MajordeskAppBundle:Parents:achat-heures.html.twig', array(
			'famille' => $famille
		));
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function achatEnCoursAction($pack)
    {
		if ($this->get('security.context')->isGranted('ROLE_PARENTS') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			if ($pack == 1 || $pack == 2) {
				$user = $this->getUser();
				$user_mail = $user->getMail();
				$famille = $user->getFamille();
					   
				return $this->render('MajordeskAppBundle:Parents:achat-en-cours.html.php', array(
					'user_mail' => $user_mail,
					'famille' => $famille,
					'pack' => $pack
				));
			}
			else {
				return $this->redirect($this->generateUrl('majordesk_app_achat_heures'));
			}
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function passerCarteAction()
    {
		$user = $this->getUser();
		$famille = $user->getFamille();

		$famille->setAutorisationPrelevement(2);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($famille);
		$em->flush();
		
		$this->get('session')->getFlashBag()->add('info', ' Vous êtes bien passé en mode "à la carte".');
			   
		return $this->redirect($this->generateUrl('majordesk_app_abonnements_factures'));
    }
	
	/**
	 * @Secure(roles="ROLE_PARENTS")
	 */
	public function annulerAbonnementAction($id_elevematiere)
    {
		$eleve_matiere = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:EleveMatiere')
							  ->find($id_elevematiere);
		
		$eleve_matiere->setPrelevementPlateforme(0);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($eleve_matiere);
		$em->flush();
		
		$this->get('session')->getFlashBag()->add('success', ' L\'abonnement a bien été annulé.');
			   
		return $this->redirect($this->generateUrl('majordesk_app_gestion_abonnements'));
    }
	
	/**
	  * MERCANET CLASSIQUE
	  */
	public function achatAutoresponseAction()
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
			
			fwrite( $fp, "-------------------ACHAT-------------------\n");
				$infos = unserialize(base64_decode($caddie));
				fwrite( $fp, "pack: ".$infos[0]."\n");
				fwrite( $fp, "famille_id: ".$infos[1]."\n");
			
			if ($response_code == '00') {
				fwrite( $fp, "Récupération famille via Id...\n");
				$famille = $this->getDoctrine()
								->getManager()
								->getRepository('MajordeskAppBundle:Famille')
								->find($infos[1]);
				fwrite( $fp, "Récupération famille via Id : OK\n");
								
				fwrite( $fp, "Incrémentation heures...\n");
				if ($infos[0]==1) {
					$famille->setHeuresAchetees($famille->getHeuresAchetees() + 100);
					$famille->setHeuresRestantes($famille->getHeuresRestantes() + 100);
				}
				else if ($infos[0]==2) {
					$famille->setHeuresAchetees($famille->getHeuresAchetees() + 300);
					$famille->setHeuresRestantes($famille->getHeuresRestantes() + 300);
				}
				fwrite( $fp, "Incrémentation heures : OK\n");
				
				fwrite( $fp, "Création paiement...\n");
				$paiement = new Paiement();
				if ($infos[0]==1) {
					$paiement->setDescription('Vous avez acheté un pack de <em>10</em> heures de cours.');
				}
				else if ($infos[0]==2) {
					$paiement->setDescription('Vous avez acheté un pack de <em>30</em> heures de cours.');
				}
				$paiement->setPack($infos[0]);
				$paiement->setMontant($amount);
				$paiement->setTransaction($transaction_id);
				$famille->addPaiement($paiement);
				fwrite( $fp, "Création paiement: OK\n");			
				
				$facture = new Facture();
				$facture->setFamille($famille);
				$dateEmission = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
				$year = $dateEmission->format('Y');
				$date_facture = $dateEmission->format('d/m/Y');
				if ($infos[0]==1) {
					$facture->setTotal(59900);
				}
				else if ($infos[0]==2) {
					$facture->setTotal(179700);
				}
				fwrite( $fp, "Création facture: OK\n");
					
				fwrite( $fp, "Validation par Flush...\n");
				$em = $this->getDoctrine()->getManager();
				$em->persist($famille);
				$em->flush();
				fwrite( $fp, "Flush : OK\n");
				
				$id_facture = $facture->getId();
				$id_famille = $famille->getId();
				fwrite( $fp, "Récupération user...\n");
				$user = $this->getUser();
				fwrite( $fp, "User : OK\n");				
				if ($user->getGender() == 1) { $gender = "Mme."; } else { $gender = "M."; }
				$nom = $user->getNom();
				
				if ($infos[0]==1) {
					$achats = array( array("designation" => "Pack 10h", "quantite" => 1, "puht" => 59900) );
				}
				else if ($infos[0]==2) {
					$achats = array( array("designation" => "Pack 30h", "quantite" => 1, "puht" => 179700) );
				}
				fwrite( $fp, "Préparation facture...\n");
				
				$this->get('knp_snappy.pdf')->generateFromHtml(
					$this->renderView(
						'MajordeskAppBundle:Admin:template-facture.html.twig',
						array(
							'id'  => $id_facture,
							'gender'  => $gender,
							'nom'  => $nom,
							'achats'  => $achats,
							'date'  => $date_facture,
						)
					),
					'/home/majorcla/documents/factures/'.$id_famille.'/'.$year.'/facture-'.$id_facture.'.pdf'
				);
				fwrite( $fp, "Facture : OK\n");
			}
		}

		fclose ($fp);
		
		return new Response();
	}
	
	
	public function achatResponseAction()
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

				fwrite( $fp, "-------------------ACHAT-------------------\n");
				$infos = unserialize(base64_decode($caddie));
				fwrite( $fp, "pack: ".$infos[0]."\n");
				fwrite( $fp, "famille_id: ".$infos[1]."\n");
				
				if ($response_code == '00' && $infos[0] == 1) {
					$session->getFlashBag()->add('info', '<i class="icon-info-sign"></i> <strong>Info :</strong> Achat validé!<br><br>Votre compte a bien été crédité de 10 heures.');
				}
				else if ($response_code == '00' && $infos[0] == 2) {
					$session->getFlashBag()->add('info', '<i class="icon-info-sign"></i> <strong>Info :</strong> Achat validé!<br><br>Votre compte a bien été crédité de 30 heures.');
				}
				else {
					$session->getFlashBag()->add('info', '<i class="icon-info-sign"></i> <strong>Info :</strong> Une erreur est peut-être survenue au cours de l\'achat.<br><br>Si vous constatez un problème, vous pouvez contacter le service client de Majorclass au 06.76.10.15.98.');
				}
			}
			fclose ($fp);
		}
		return $this->redirect($this->generateUrl('majordesk_app_abonnements_factures'));
    }
}
