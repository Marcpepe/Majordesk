<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Majordesk\AppBundle\Entity\Ticket;
use Majordesk\AppBundle\Entity\Paiement;
use Majordesk\AppBundle\Form\Type\TicketProfesseurType;

class ProfesseurController extends Controller
{
	/**
	 * @Secure(roles="ROLE_PROF")
	 */
	public function indexProfesseurAction()
    {
		if ($this->get('security.context')->isGranted('ROLE_PROF') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$user = $this->getUser();
			$eleves = $user->getEleves();
			
			$tickets = $this->getDoctrine()
						    ->getManager()
						    ->getRepository('MajordeskAppBundle:Ticket')
						    ->getLastTicketsByProfesseur($user->getId(), 3);
			
			$paiements = $this->getDoctrine()
							  ->getManager()
							  ->getRepository('MajordeskAppBundle:Paiement')
							  ->getPaiementsByProfesseur($user->getId());
			
			
			$ticket = new Ticket();
			$form = $this->createForm( new TicketProfesseurType($user->getId()), $ticket );
			
			$request = $this->getRequest();
			if ($request->getMethod() == 'POST') 
			{
				$form->bind($request);

				if ($form->isValid()) 
				{
					$eleve = $ticket->getEleve();
					$famille = $eleve->getFamille();
					$heuresRestantes = $famille->getHeuresRestantes();
					
					$ticket->setEleve($eleve);
					$ticket->setProfesseur($user);
					
					$paymentAuthorized = false;
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
					
					if ($paymentAuthorized) {
						$professeur = $user;
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
						$this->get('session')->getFlashBag()->add('info', ' Le cours a bien été déclaré.');
					
						// $session->getFlashBag()->add('info', ' Le cours s\'est terminé avec succès !');

						// $session->remove('matiere_cours');
						// $session->remove('type_cours');
						// $session->remove('etape_cours');
						// $session->remove('debut_cours');
						// $session->remove('professeur_cours');
						// return $this->redirect($this->generateUrl('majordesk_app_index_eleve'));
					}
					else {
						$this->get('session')->getFlashBag()->add('incorrect_pass', ' Mot de passe du parent incorrect.');
					}
				
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
			
			// $date_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			// $date_from->sub(new \DateInterval('PT1H'));
			// $date_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			// $date_to->add(new \DateInterval('P14D'));
			
			// $cal_events_proches = $this->getDoctrine()
									   // ->getManager()
									   // ->getRepository('MajordeskAppBundle:CalEvent')
									   // ->getProfesseurCalEventsProches($user->getId(), $date_from, $date_to);
									   
			// $cal_events_proches_to_confirm = 0;
			// foreach($cal_events_proches as $cal_event) {
				// if ($cal_event->getReservation() == 1) {
					// $cal_events_proches_to_confirm++;
				// }
			// }
			
			return $this->render('MajordeskAppBundle:Professeur:index-professeur.html.twig', array(
				'tickets' => $tickets,
				'paiements' => $paiements,
				'eleves' => $eleves,
				'form' => $form->createView(),
				// 'cal_events_proches' => $cal_events_proches,
				// 'cal_events_proches_to_confirm' => $cal_events_proches_to_confirm
			));
		}
    }
	
	/**
	 * @Secure(roles="ROLE_PROF")
	 */
    public function coursDonnesAction()
    {
		$user = $this->getUser();
		
		$first_day_this_month = new \Datetime("first day of this month", new \DateTimeZone('Europe/Paris'));
		$first_day_last_month = new \Datetime("first day of last month", new \DateTimeZone('Europe/Paris'));
		$first_day_next_month = new \Datetime("first day of next month", new \DateTimeZone('Europe/Paris'));
		
		$tickets_this_month = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:Ticket')
								   ->getTicketsByProfesseur($user->getId(), $first_day_this_month, $first_day_next_month);
		
		$tickets_last_month = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MajordeskAppBundle:Ticket')
								   ->getTicketsByProfesseur($user->getId(), $first_day_last_month, $first_day_this_month);
		
		$date_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		$date_from->sub(new \DateInterval('P3M'));
		$date_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
		
		$cal_events = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MajordeskAppBundle:CalEvent')
						   ->getProfesseurCalEvents($user->getId(), $date_from, $date_to);
		
		$paiements = $this->getDoctrine()
						  ->getManager()
						  ->getRepository('MajordeskAppBundle:Paiement')
						  ->getPaiementsByProfesseur($user->getId());
		
		$encours = 0;
		foreach($tickets_this_month as $ticket) {
			$encours += $ticket->getQuantite() / 10 * 25;
		}
		
		$total = 0;
		foreach($tickets_last_month as $ticket) {
			$total += $ticket->getQuantite() / 10 * 25;
		}
		
		return $this->render('MajordeskAppBundle:Professeur:cours-donnes.html.twig', array(
			'tickets_this_month' => $tickets_this_month,
			'tickets_last_month' => $tickets_last_month,
			'cal_events' => $cal_events,
			'paiements' => $paiements,
			'encours' => $encours,
			'total' => $total,
		));
	}
	
	/**
	 * @Secure(roles="ROLE_PROF")
	 */
    public function gestionDevoirsAction($id_eleve)
    {
		$user = $this->getUser();
		$eleves = $user->getEleves();
		
		$eleve = $this->getDoctrine()
					  ->getManager()
					  ->getRepository('MajordeskAppBundle:Eleve')
					  ->find($id_eleve);
		
		if (empty($eleve)) {
			throw new \Exception('Aucun élève ne correspond à la demande.');
		}
		if (!in_array($eleve, $eleves->toArray())) {
			throw new \Exception('Cet élève ne t\'est pas assigné.');
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
