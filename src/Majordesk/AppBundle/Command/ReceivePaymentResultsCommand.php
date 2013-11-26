<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class ReceivePaymentResultsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:receiving:receive')
            ->setDescription('Recevoir un ABOREP01.zip contenant un fichier requete des ordres de paiements à passer.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$date = date('d/m/Y \à H:m:i');
		$logs=fopen('/home/majorcla/mercanet/atos/reponse/logs.txt', 'a+');
		
		$MAX_ATTEMPTS = 5;
		$attempt = 1;
		$error_no = -1;
		
		while($error_no != 0 && $attempt <= $MAX_ATTEMPTS) {			
			fwrite( $logs, $date."  ESSAI ".$attempt."\r\n");
			$output->writeln('ESSAI '.$attempt);
			
			// ETAPE 1 : Récupération du nom du fichier	
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL,'ftp://ubzmjcla:Cx4x-2FzhW@ftpssl.pci.aw.atosorigin.com:10021');
			curl_setopt($ch, CURLOPT_CAINFO, "/home/majorcla/public_html/mercanet/certificates/root-ftpssl.pci.aw.atosorigin.com.cer");	
			curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
			curl_setopt($ch, CURLOPT_SSLVERSION,3);
			curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
			curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$returnedFilename = curl_exec($ch);
			
			curl_close($ch);
			
			// ETAPE 2 : Download
			$ch = curl_init();
			
			if (!file_exists('/home/majorcla/mercanet/atos/reponse/'.date('Y-m-d'))) { // ou alors CURLOPT_FTP_CREATE_MISSING_DIRS
				mkdir('/home/majorcla/mercanet/atos/reponse/'.date('Y-m-d'), 0777, true);
			}
			
			$file_path = '/home/majorcla/mercanet/atos/reponse/'.date('Y-m-d').'/ABOREP01.ZIP';
			$file = fopen($file_path, 'w+');

			// Connexion and download to new filename
			curl_setopt($ch, CURLOPT_URL,'ftp://ftpssl.pci.aw.atosorigin.com/'.$returnedFilename);
			curl_setopt($ch, CURLOPT_PORT, 10021);
			curl_setopt($ch, CURLOPT_USERPWD, "ubzmjcla:Cx4x-2FzhW");
			curl_setopt($ch, CURLOPT_CAINFO, "/home/majorcla/public_html/mercanet/certificates/root-ftpssl.pci.aw.atosorigin.com.cer");	
			curl_setopt($ch, CURLOPT_SSLVERSION,3);
			curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
			curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
			
			// Download
			curl_setopt($ch, CURLOPT_FILE, $file); 	
			
			// Uncomment for debug
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE); 

			$returnCode = curl_exec($ch);
			
			$error_no = curl_errno($ch);

			curl_close($ch);
			
			fclose($file); 
			
			$date = date('d/m/Y \à H:m:i');
			if ($error_no == 0) {
				fwrite( $logs, $date."  Aucune erreur cURL\r\n");
				$output->writeln('Aucune erreur cURL');
			} else {
				fwrite( $logs, $date."  Code erreur : ".$error_no."\r\n");
				$output->writeln("Code erreur : ".$error_no);
			}
			
			$em = $this->getContainer()->get('doctrine')->getManager();
			$last_requete = $em->getRepository('MajordeskAppBundle:Requete')
							   ->getLastRequete();
			$last_requete->setStatutReception(1);
			$em->persist($last_requete);
			$em->flush();
			
			$attempt++;
		}
		if ($error_no != 0) {
			fwrite( $logs, $date."  Les 5 tentatives ont échouées\r\n");
			$output->writeln("Les 5 tentatives ont échouées");
		}
		
		$date = date('d/m/Y \à H:m:i');
		fwrite( $logs, $date."  Statut de réception mis a jour.\r\n");
		
		fclose($logs);
    }
}