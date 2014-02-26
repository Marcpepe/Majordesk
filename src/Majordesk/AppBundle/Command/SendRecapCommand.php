<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendRecapCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('majordesk:recap:send')
            ->setDescription('Envoi un mail récapitulatif des cours pris pendant la semaine aux familles dont le paramètre "alerte" vaut 1.')
			->addOption('test', null, InputOption::VALUE_NONE, 'Si définie, le seul destinataire sera marc.perrinpelletier@gmail.fr')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$container = $this->getContainer();
		$em = $container->get('doctrine')->getManager();
		
		$destinataires = $em->getRepository('MajordeskAppBundle:Client')
					        ->getDestinatairesRecap();
		
		$nb_destinataires = count($destinataires);
		$output->writeln("Nombre de destinataire(s) ".$nb_destinataires);
		
		foreach($destinataires as $destinataire) {
			if ($input->getOption('test')) {
				$mail = 'marc@majorclass.fr';
			} else {
				$mail = $destinataire->getMail();
			}	
			
			$parente = $destinataire->getGender();
			if ($parente % 2 == 0) {
				$gender = 'Cher M.';
			} else {
				$gender = 'Chère Mme.';
			}
			$nom = $destinataire->getNom();
			
			$tickets = $em->getRepository('MajordeskAppBundle:Ticket')
					      ->getTicketsThisWeek($destinataire->getFamille()->getId());		
			$liste = '';
			
			$nb_tickets = count($tickets);
			$output->writeln("\n\nFamille : ".$nom." (".$mail.")");
			$output->writeln("Nombre de ticket(s) ".$nb_tickets);
			
			if (!empty($tickets)){			
				
				foreach($tickets as $ticket) {
					$qte = $ticket->getQuantite();
					if ($qte==10) {
						$temps = '1h';
					} else if ($qte==15) {
						$temps = '1h30';
					} else if ($qte==20) {
						$temps = '2h';
					} else if ($qte==25) {
						$temps = '2h30';
					} else if ($qte==30) {
						$temps = '3h';
					} else if ($qte==35) {
						$temps = '3h30';
					} else if ($qte==40) {
						$temps = '4h';
					} else if ($qte==45) {
						$temps = '4h30';
					} else if ($qte==50) {
						$temps = '5h';
					}
					$jr = $ticket->getDateCours()->format('N');
					if ($jr==1) {
						$jour = 'Lundi';
					} else if ($jr==2) {
						$jour = 'Mardi';
					} else if ($jr==3) {
						$jour = 'Mercredi';
					} else if ($jr==4) {
						$jour = 'Jeudi';
					} else if ($jr==5) {
						$jour = 'Vendredi';
					} else if ($jr==6) {
						$jour = 'Samedi';
					} else if ($jr==7) {
						$jour = 'Dimanche';
					} else {
						$jour = '';
					}
					$ms = $ticket->getDateCours()->format('n');
					if ($ms==1) {
						$mois = 'Janvier';
					} else if ($ms==2) {
						$mois = 'Février';
					} else if ($ms==3) {
						$mois = 'Mars';
					} else if ($ms==4) {
						$mois = 'Avril';
					} else if ($ms==5) {
						$mois = 'Mai';
					} else if ($ms==6) {
						$mois = 'Juin';
					} else if ($ms==7) {
						$mois = 'Juillet';
					} else if ($ms==8) {
						$mois = 'Août';
					} else if ($ms==9) {
						$mois = 'Septembre';
					} else if ($ms==10) {
						$mois = 'Octobre';
					} else if ($ms==11) {
						$mois = 'Novembre';
					} else if ($ms==12) {
						$mois = 'Décembre';
					} else {
						$mois = '';
					}
				
					$liste .= '<li>';
					$liste .= $jour.' '.$ticket->getDateCours()->format('j').' '.$mois.' : Cours de '.$temps.' avec '.$ticket->getProfesseur()->getUsername().' ('.$ticket->getEleve()->getUsername().')';
					$liste .= '</li>';
				}
			
				$message = \Swift_Message::newInstance()
									->setSubject('Majorclass - Compte-rendu de la semaine')
									->setFrom(array('ne-pas-repondre@majorclass.fr' => 'Majorclass'))
									->setTo($mail)
									->setBody($container->get('templating')->render('MajordeskAppBundle:Template:recap.html.twig', array('gender' => $gender, 'nom' => $nom, 'liste' => $liste)), 'text/html')
								;
								$container->get('mailer')->send($message);
								$transport = $container->get('swiftmailer.transport.real');						
								$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
				$output->writeln("Mail envoyé à ".$mail);
			}
		}
    }
}