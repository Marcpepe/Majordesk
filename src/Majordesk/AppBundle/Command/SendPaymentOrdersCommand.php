<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class SendPaymentOrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:sending:send')
            ->setDescription('Envoyer un ABOREQ01.zip contenant un fichier requete des ordres de paiements à passer.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {	
		$ch = curl_init();
		
		// file
		$file = '/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/'.date('Y-m-d').'/ABOREQ01.zip';
		$file_handle = fopen($file, 'r');
		
		// Connexion settings
		curl_setopt($ch, CURLOPT_URL,'ftp://ftpssl.pci.aw.atosorigin.com/ABOREQ01.zip');
		curl_setopt($ch, CURLOPT_PORT, 10021);
		curl_setopt($ch, CURLOPT_USERPWD, "ubzmjcla:Cx4x-2FzhW");
		curl_setopt($ch, CURLOPT_CAINFO, "/home/majorcla/public_html/mercanet/certificates/root-ftpssl.pci.aw.atosorigin.com.cer");	
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
		curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
		
		// Upload
		curl_setopt($ch, CURLOPT_UPLOAD, 1);
		curl_setopt($ch, CURLOPT_INFILE, $file_handle);
		curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
		
		// Uncomment for debug
		curl_setopt($ch, CURLOPT_VERBOSE, TRUE); 
		
		$returnCode = curl_exec($ch);
		
		$error_no = curl_errno($ch);
		$errors = curl_error($ch);

		curl_close($ch);
		
		$logs=fopen('/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/atos/requete/logs.txt', 'a+');
		$date = date('d/m/Y \à H:m:i');
		if ($error_no == 0) {
			fwrite( $logs, $date.$returnCode."  Aucune erreur cURL\r\n");
			$output->writeln('Aucune erreur cURL');
		} else {
			fwrite( $logs, $date.$returnCode."  ".$error_no." erreur(s) cURL\r\n");
			$output->writeln($error_no." erreur(s) cURL : ".$errors);
		}
		
		$em = $this->getContainer()->get('doctrine')->getManager();
		$last_requete = $em->getRepository('MajordeskAppBundle:Requete')
						   ->getLastRequete();
		$last_requete->setStatutEnvoi(1);
		$em->persist($last_requete);
		$em->flush();
		
		fclose($logs);
    }
}