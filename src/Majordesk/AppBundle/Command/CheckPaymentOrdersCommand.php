<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Majordesk\AppBundle\Entity\Requete;
use Majordesk\AppBundle\Entity\Paiement;

class CheckPaymentOrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:sending:prepare')
            ->setDescription('Genere les paiements de renouvellement et le fichier des paiements en attente')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$em = $this->getContainer()->get('doctrine')->getManager();
		
		// Nouvelle requete
		$logs=fopen('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/logs.txt', 'a+');
		
		$date = date('d/m/Y \à H:m:i');		
		fwrite( $logs, $date."  Préparation d\'une nouvelle requete...\r\n");
		$last_requete = $em->getRepository('MajordeskAppBundle:Requete')
						   ->getLastRequete();
		if(!empty($last_requete)){
			$new_numero_fichier = $last_requete->getNumeroFichier()+1;
			$numero_fichier = str_pad($new_numero_fichier, 6, '0', STR_PAD_LEFT);
		} else {
			$new_numero_fichier = 1;
			$numero_fichier = '000001';
		}
		$date = date('d/m/Y \à H:m:i');
		fwrite( $logs, $date."  Nouvelle requete de numero ".$numero_fichier."\r\n");
		$output->writeln('Nouvelle requete de numero '.$numero_fichier);
		
		$requete = new Requete();
		$requete->setNumeroFichier($new_numero_fichier);
		$em->persist($requete);
		
		$date = date('d/m/Y \à H:m:i');
		fwrite( $logs, $date."  Sauvegarde OK\r\n");
		$output->writeln('Sauvegarde OK');
	
		// Génération des paiements liés aux renouvellements plateforme
        $eleve_matieres = $em->getRepository('MajordeskAppBundle:EleveMatiere')
							 ->getRenewingPlateformAccess();		
		foreach($eleve_matieres as $eleve_matiere) {
			$eleve = $eleve_matiere->getEleve();
			$famille = $eleve->getFamille();
			$paiement = new Paiement();
			$paiement->setDescription('Renouvellement pour 1 mois de l\'abonnement à la Plateforme '.$eleve_matiere->getMatiere()->getNom().' de '.$eleve->getUsername().'.');
			$paiement->setFamille($famille);
			$paiement->setPack('9');
			$paiement->setMontant(5990);
			$paiement->setTransaction(1);  // 0: annulé, 1: en cours, 2: validé, 3: non réglé
			$paiement->setEleveMatiere($eleve_matiere);
			$em->persist($paiement);
		}
		$em->flush();
		
		$output->writeln('Generation des paiements...');
		$date = date('d/m/Y \à H:m:i');
		fwrite( $logs, $date."  ".count($eleve_matieres)." nouveaux paiements ont ete crees.\r\n");
		$output->writeln('"<info>'.count($eleve_matieres).'</info>" nouveaux paiements ont ete crees.');
		
		// Génération du fichier pour l'envoi FTP/SSL à Atos
        $paiements = $em->getRepository('MajordeskAppBundle:Paiement')
						->getPendingPaiements();
		
		if (!file_exists('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/'.date('Y-m-d'))) {
			mkdir('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/'.date('Y-m-d'), 0777, true);
		}
		if (!file_exists('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/'.date('Y-m-d'))) {
			mkdir('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/'.date('Y-m-d'), 0777, true);
		}
		
		$reqfile='/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/'.date('Y-m-d').'/requete';		
		
		$req=fopen($reqfile, "w+");
		
		// Préparation
		$jour = date('d');
		$mois = date('m');
		$annee = date('Y');
		$heures = date('H');
		$minutes = date('i');
		$secondes = date('s');
		
		// Entête
		fwrite( $req, '00fr078949346700011'.$jour.$mois.$annee.$heures.$minutes.$secondes.$numero_fichier.'03                                                                                                                                                               ');
		
		// Détails
		$no_enregistrement = 1;
		$nb_enregistrements = 0;
		foreach($paiements as $paiement) {
			$abonnement = $paiement->getFamille()->getAbonnement();
			if (!empty($abonnement)) {
				$montant = $paiement->getMontant();
				$reference = '                                ';
				$eleve_matiere = $paiement->getEleveMatiere();
				$id_paiement = $paiement->getId();
				if (!empty($eleve_matiere)) {
					$reference = str_pad($eleve_matiere->getId(), 32, ' ', STR_PAD_RIGHT);
				}
				fwrite( $req, '03fr078949346700011'.str_pad($no_enregistrement, 6, '0', STR_PAD_LEFT).str_pad($abonnement, 8, ' ', STR_PAD_RIGHT).str_pad($id_paiement, 6, '0', STR_PAD_LEFT).str_pad($montant, 12, '0', STR_PAD_LEFT).'978                                                  '.$reference.'                                                                ');
				$no_enregistrement++;
				$nb_enregistrements++;
			}	
			$abonnement = null;
		}
		
		// Fin
		fwrite( $req, '09fr078949346700011'.$jour.$mois.$annee.$heures.$minutes.$secondes.$numero_fichier.'03'.str_pad($nb_enregistrements, 6, '0', STR_PAD_LEFT).'                                                                                                                                                         ');
		
		fclose($req);
		
		$output->writeln('Generation du fichier pour l\'envoi FTP/SSL a Atos...');
		$date = date('d/m/Y \à H:m:i');
		fwrite( $logs, $date."  ".$nb_enregistrements." ordres de paiements ont ete ajoutes au fichier.\r\n");
		$output->writeln('"<info>'.$nb_enregistrements.'</info>" ordres de paiements ont ete ajoutes au fichier.');  

		$zip = new \ZipArchive();
		if ($zip->open('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/'.date('Y-m-d').'/ABOREQ01.zip', \ZIPARCHIVE::CREATE) != true) {
			$date = date('d/m/Y \à H:m:i');
			fwrite( $logs, $date.'  Echec de création du Zip.');
		}
		$zip->addFile($reqfile, 'requete');
		$zip->close(); 
		
		fclose($logs);
    }
}