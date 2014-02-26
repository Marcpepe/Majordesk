<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Majordesk\AppBundle\Entity\Paiement;
use Majordesk\AppBundle\Entity\Facture;

class GenerateFacturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('majordesk:factures:generate')
            ->setDescription('Génère une facture pour chaque famille qui a fait au moins 1 paiement : ce script doit être exécuté au premier du mois.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$output->writeln("Début du script");
		$output->writeln("---------------\n\n");
	
		$container = $this->getContainer();
		$em = $container->get('doctrine')->getManager();
		
		$familles = $em->getRepository('MajordeskAppBundle:Famille')
					        ->findAll();
			
		$output->writeln("Familles récupérées: ".count($familles)."\n");
		
		foreach($familles as $famille) {
			$paiements = $em->getRepository('MajordeskAppBundle:Paiement')
						    // ->getCurrentMonthlyPaiements($famille->getId());
						    ->getMonthlyPaiements($famille->getId());
			
			$output->writeln("\n//// Famille #".$famille->getId().": ".count($paiements)." paiement(s)");
			
			if (!empty($paiements)) {			
				$achats = array();
				$total = 0;		
				
				foreach( $paiements as $paiement ) {
					$achat = array();
					$montant = $paiement->getMontant();
					$quantite = $montant / 5990;
					$ticket = $paiement->getTicket();
					if (!empty($ticket)) {
						$dateCours = $ticket->getDateCours();
						$dateCours = $dateCours->format('d/m/Y');
						$achat['designation'] = "Cours le ".$dateCours;
						$achat['quantite'] = $quantite;
						$achat['puht'] = 5990; // 5446; // 5446*1.1=5990
						$total += $montant;
					} else {
						$achat['designation'] = "Abonnement Plateforme";
						$achat['quantite'] = 1;
						$achat['puht'] = $montant; //5990; // 5446; // 5446*1.1=5990
						$total += $montant;
					}				
					$achats[] = $achat;				
				}	
				
				$facture = new Facture();
				$facture->setFamille($famille);
				$dateEmission = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
				$dateEmission->sub(new \DateInterval('P1D'));
				$facture->setDateEmission($dateEmission);
				$year = $dateEmission->format('Y');
				$date_facture = $dateEmission->format('d/m/Y');
				$entete_facture = $dateEmission->format('Ymd');
				$facture->setTotal($total);

				$em->persist($facture);
				$output->writeln("Flush...");
				$em->flush();
				$output->writeln("Flush: Ok");
				// $output->writeln("Flush: No");
				
				
				$id_facture = $facture->getId();
				// $id_facture = "ESSAI";
				$id_famille = $famille->getId();

				
				$parente = $famille->getGender();
				if ($parente % 2 == 0) {
					$gender = 'M.';
				} else {
					$gender = 'Mme.';
				}

				$nom = $famille->getNom();
				
				$output->writeln("Génération...");
				
				$container->get('knp_snappy.pdf')->generateFromHtml(
					$container->get('templating')->render(
						'MajordeskAppBundle:Template:facture.html.twig',
						array(
							'id'  => $id_facture,
							'gender'  => $gender,
							'nom'  => $nom,
							'achats'  => $achats,
							'date'  => $date_facture,
						)
					),
					'/home/majorcla/public_html/majordesk/production/majorclass.fr/current/documents/factures/'.$id_famille.'/'.$year.'/'.$entete_facture.'-facture-'.$id_facture.'.pdf'
				);
				$output->writeln("Génération: Ok");
				$output->writeln("//// ------------- ////\n");
			}
		}
    }
}