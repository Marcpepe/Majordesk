<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Majordesk\AppBundle\Entity\Paiement;

class UpdateDatabaseAfterPaymentResultsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:receiving:update')
            ->setDescription('Ouvre le .zip reçu, lit le fichier et met a jour la BDD.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$container = $this->getContainer(); 
		$em = $container->get('doctrine')->getManager();
	
		$logs=fopen('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/logs.txt', 'a+');
		
		$date = date('d/m/Y \à H:m:i');		
		fwrite( $logs, $date."  Ouverture du zip...\r\n");
		$output->writeln('Ouverture du zip...');	
		$zip = zip_open('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/'.date('Y-m-d').'/ABOREP01.ZIP');
		
		// Ouverture du Zip
		if (!is_int($zip)) {
			$date = date('d/m/Y \à H:m:i');
			fwrite( $logs, $date."  Zip identifié...\r\n");
			$output->writeln('Zip identifié...');
			
			// Vérification de présence du fichier
			$zip_entry = zip_read($zip);
			if ($zip_entry) {
				$date = date('d/m/Y \à H:m:i');
				fwrite( $logs, $date."  Vérification de présence OK...\r\n");
				$output->writeln('Vérification de présence OK...');
				
				// Ouverture du fichier
				if (zip_entry_open($zip, $zip_entry, "r")) {
					$date = date('d/m/Y \à H:m:i');
					fwrite( $logs, $date."  Ouverture OK...\r\n");
					$output->writeln('Ouverture OK...');
					
					// Extraction du fichier
					$rep=fopen('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/'.date('Y-m-d').'/reponse', 'w+');
					fwrite($rep, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					fclose($rep);
					$date = date('d/m/Y \à H:m:i');
					fwrite( $logs, $date."  Extraction OK...\r\n");
					$output->writeln('Extraction OK...');
					
					// Traitement
					$reponse = file_get_contents('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/reponse/'.date('Y-m-d').'/reponse');

					$code = substr($reponse, 41, 2);
					fwrite( $logs, $date."  Code traitement : ".$code."\r\n");
					$output->writeln('Code traitement : '.$code);
					if ($code == '00') {
						fwrite( $logs, $date."  Taille fichier : ".strlen($reponse)."\r\n");
						$output->writeln('Taille fichier : '.strlen($reponse));
						if (strlen($reponse) > 400) {
							$results = substr($reponse, 200, strlen($reponse)-400);
							$nb_enregistrements = floor(strlen($results)/200);
							fwrite( $logs, $date."  Taille enregistrements : ".strlen($results)." et nb enregistrements : ".$nb_enregistrements."\r\n");
							$output->writeln("Taille enregistrements : ".strlen($results)." et nb enregistrements : ".$nb_enregistrements);
							
							$messageCron = '';
							$problemCron = 0;
							
							for($i=0;$i<=$nb_enregistrements-1;$i++) {
								$date = date('d/m/Y \à H:m:i');
								$id_paiement = trim(substr($results, 200*$i + 33, 6));
								$id_elevematiere = trim(substr($results, 200*$i + 104, 32));
								if ( substr($results, 200*$i + 136, 2) == "00" ) { // paiement accepté
									if ($id_elevematiere != '') {
										fwrite( $logs, $date."  Paiement #".$id_paiement." accepté avec elevematiere #".$id_elevematiere.".\r\n");
										$output->writeln("Paiement #".$id_paiement." accepté avec elevematiere #".$id_elevematiere.".");
										$paiement = $em->getRepository('MajordeskAppBundle:Paiement')
													   ->find($id_paiement);
										if (!empty($paiement)) {
											$paiement->setTransaction(2);
											$eleve_matiere = $em->getRepository('MajordeskAppBundle:EleveMatiere')
																->find($id_elevematiere);
											if (!empty($eleve_matiere)) {			
												$data_abo = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
													$data_abo->add(new \DateInterval('P1MT3H'));
												$eleve_matiere->setDateAbonnement($data_abo);
												$em->persist($eleve_matiere);
													$nom_famille = $paiement->getFamille()->getNom();
												$messageCron .= '<li>Paiement n°'.$paiement->getId().' : 1 renouvellement de plateforme (Famille: '.$nom_famille.', $id_eleve_matiere : '.$id_elevematiere.')</li>';
											}
											else {
												$problemCron++;
													$nom_famille = $paiement->getFamille()->getNom();
												$messageCron .= '<li>Paiement n°'.$paiement->getId().' : 1 renouvellement de plateforme (Famille: '.$nom_famille.', ATTENTION: aucun eleve_matiere associé, la date de validité n\'a pas été mise à jour)</li>';
											}
											$em->persist($paiement);
										}
										else {
											$problemCron++;
											$messageCron .= '<li>Paiement n°XXX : 1 renouvellement de plateforme (ATTENTION: $id_paiement introuvable)</li>';
										}								
									}
									else {
										fwrite( $logs, $date."  Paiement #".$id_paiement." accepté sans id_elevematiere.\r\n");
										$output->writeln("Paiement #".$id_paiement." accepté sans id_elevematiere.");
										$paiement = $em->getRepository('MajordeskAppBundle:Paiement')
													   ->find($id_paiement);
										if (!empty($paiement)) {
											$paiement->setTransaction(2);
											$em->persist($paiement);
												$nom_famille = $paiement->getFamille()->getNom();
												$montant = $paiement->getMontant() / 100;
												$montant = number_format($montant, 2, ',', ' ');
											$messageCron .= '<li>Paiement n°'.$paiement->getId().' : 1 cours payé (Famille: '.$nom_famille.', Montant: '.$montant.'€)</li>';
										}
										else {
											$problemCron++;
											$messageCron .= '<li>Paiement n°XXX : 1 cours payé (ATTENTION: $id_paiement introuvable)</li>';
										}
									}
								}
								else { // paiement refusé
									$problemCron++;
									fwrite( $logs, $date."  Paiement #".$id_paiement." REFUSE.\r\n");
									$output->writeln("Paiement #".$id_paiement." REFUSE.");
									$paiement = $em->getRepository('MajordeskAppBundle:Paiement')
												   ->find($id_paiement);
									if (!empty($paiement)) {
										$paiement->setTransaction(3);
										$em->persist($paiement);
											$nom_famille = $paiement->getFamille()->getNom();
											$montant = $paiement->getMontant() / 100;
											$montant = number_format($montant, 2, ',', ' ');
										$messageCron .= '<li>Paiement n°'.$paiement->getId().' REFUSE : Famille '.$nom_famille.', Montant '.$montant.'€ (ATTENTION PAIEMENT REFUSE)</li>';
									}
									else {
										$messageCron .= '<li>Paiement n°XXX : 1 cours payé (ATTENTION: $id_paiement introuvable, LE PAIEMENT AVAIT ETE REFUSE)</li>';
									}
								}
							}
							$em->flush();
							
							$dateRapport = date('d/m/Y');
							
							if ($problemCron == 0) {
								$message = \Swift_Message::newInstance()
									->setSubject('Rapport du Cron')
									->setFrom(array('cron@majorclass.fr' => 'Majorclass Cron'))
									->setTo(array('marc@majorclass.fr','jonathan@majorclass.fr'))
									->setBody($container->get('templating')->render('MajordeskAppBundle:Template:rapport-cron.html.twig', array('dateRapport' => $dateRapport, 'messageCron' => $messageCron)), 'text/html')
								;
							} else {
								$message = \Swift_Message::newInstance()
									->setSubject('Rapport du Cron ('.$problemCron.' problème(s))')
									->setFrom(array('cron@majorclass.fr' => 'Majorclass Cron'))
									->setTo(array('marc@majorclass.fr','jonathan@majorclass.fr'))
									->setBody($container->get('templating')->render('MajordeskAppBundle:Template:rapport-cron.html.twig', array('dateRapport' => $dateRapport, 'messageCron' => $messageCron)), 'text/html')
								;
							}
							$container->get('mailer')->send($message);
							$transport = $container->get('swiftmailer.transport.real');						
							$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
							
						}
						else {
							$date = date('d/m/Y \à H:m:i');
							fwrite( $logs, $date."  Aucun traitement nécessaire. \r\n");
							$output->writeln('Aucun traitement nécessaire ');
							$erreur = 'Aucun (aucun traitement nécessaire)';
							$message = \Swift_Message::newInstance()
								->setSubject('Traitement Cron OK')
								->setFrom('cron@majorclass.fr')
								->setTo('marc@majorclass.fr')
								->setBody($container->get('templating')->render('MajordeskAppBundle:Template:erreur-cron.html.twig', array('erreur' => $erreur)), 'text/html')
							;
							$container->get('mailer')->send($message);
							$transport = $container->get('swiftmailer.transport.real');						
							$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
						}
					}
					else {
						$erreur = 'Fichier erroné (code : '.$code.')';
						$message = \Swift_Message::newInstance()
							->setSubject('Urgent : Erreur Cron')
							->setFrom('cron@majorclass.fr')
							->setTo('marc@majorclass.fr')
							->setBody($container->get('templating')->render('MajordeskAppBundle:Template:erreur-cron.html.twig', array('erreur' => $erreur)), 'text/html')
						;
						$container->get('mailer')->send($message);
						$transport = $container->get('swiftmailer.transport.real');						
						$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
					}
				}
				else {
					$date = date('d/m/Y \à H:m:i');
					fwrite( $logs, $date."  Ouverture impossible...\r\n");
					$output->writeln('Ouverture impossible...');
					$erreur = 'Ouverture impossible';
					$message = \Swift_Message::newInstance()
						->setSubject('Urgent : Erreur Cron')
						->setFrom('cron@majorclass.fr')
						->setTo('marc@majorclass.fr')
						->setBody($container->get('templating')->render('MajordeskAppBundle:Template:erreur-cron.html.twig', array('erreur' => $erreur)), 'text/html')
					;
					$container->get('mailer')->send($message);				
					$transport = $container->get('swiftmailer.transport.real');						
					$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
				}
				
			}
			else {
				$date = date('d/m/Y \à H:m:i');
				fwrite( $logs, $date."  Fichier non présent...\r\n");
				$output->writeln('Fichier non présent...');
				$erreur = 'Fichier non présent';
				$message = \Swift_Message::newInstance()
					->setSubject('Urgent : Erreur Cron')
					->setFrom('cron@majorclass.fr')
					->setTo('marc@majorclass.fr')
					->setBody($container->get('templating')->render('MajordeskAppBundle:Template:erreur-cron.html.twig', array('erreur' => $erreur)), 'text/html')
				;
				$container->get('mailer')->send($message);				
				$transport = $container->get('swiftmailer.transport.real');						
				$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
			}
		}
		else {
			$date = date('d/m/Y \à H:m:i');
			fwrite( $logs, $date."  Zip inexistant...\r\n");
			$output->writeln('Zip inexistant...');
			$erreur = 'Zip inexistant';
			$message = \Swift_Message::newInstance()
				->setSubject('Urgent : Erreur Cron')
				->setFrom('cron@majorclass.fr')
				->setTo('marc@majorclass.fr')
				->setBody($container->get('templating')->render('MajordeskAppBundle:Template:erreur-cron.html.twig', array('erreur' => $erreur)), 'text/html')
			;
			$container->get('mailer')->send($message);				
			$transport = $container->get('swiftmailer.transport.real');						
			$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
		}
    }
}