<?php

namespace Majordesk\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

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
			
			$date_from = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$date_from->sub(new \DateInterval('PT1H'));
			$date_to = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
			$date_to->add(new \DateInterval('P14D'));
			
			$cal_events_proches = $this->getDoctrine()
									   ->getManager()
									   ->getRepository('MajordeskAppBundle:CalEvent')
									   ->getProfesseurCalEventsProches($user->getId(), $date_from, $date_to);
									   
			$cal_events_proches_to_confirm = 0;
			foreach($cal_events_proches as $cal_event) {
				if ($cal_event->getReservation() == 1) {
					$cal_events_proches_to_confirm++;
				}
			}
			
			return $this->render('MajordeskAppBundle:Professeur:index-professeur.html.twig', array(
				'eleves' => $eleves,
				'cal_events_proches' => $cal_events_proches,
				'cal_events_proches_to_confirm' => $cal_events_proches_to_confirm
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
