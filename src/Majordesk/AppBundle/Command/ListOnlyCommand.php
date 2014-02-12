<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class ListOnlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:listonly')
            ->setDescription('Liste les fichiers présents sur le serveur Atos')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		// curl --list-only --cacert /home/majorcla/public_html/mercanet/certificates/root-ftpssl.pci.aw.atosorigin.com.cer --sslv3 --ftp-ssl --show-error --verbose ftp://ubzmjcla:Cx4x-2FzhW@ftpssl.pci.aw.atosorigin.com:10021
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,'ftp://ubzmjcla:Cx4x-2FzhW@ftpssl.pci.aw.atosorigin.com:10021');
		curl_setopt($ch, CURLOPT_CAINFO, "/home/majorcla/public_html/majordesk/production/majorclass.fr/current/mercanet/certificates/root-ftpssl.pci.aw.atosorigin.com.cer");	
		curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);
		curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Uncomment for debug
		// curl_setopt($ch, CURLOPT_VERBOSE, TRUE); 

		$returnCode = curl_exec($ch);
		
		$error_no = curl_errno($ch);
		
		curl_close($ch);
		
		$output->writeln('La liste des fichiers est : '.$returnCode);
		
		$date = date('d/m/Y \à H:m:i');
		if ($error_no == 0) {
			$output->writeln('Aucune erreur cURL');
		} else {
			$output->writeln($error_no." erreur(s) cURL");
		}
    }
}