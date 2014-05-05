<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CardExpirationMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('majordesk:cardExpirationMail:send')
            ->setDescription('Envoi un mail le 15 et le 24 du mois pour les familles dont le moyen de paiement expire le mois prochain.')
			->addOption('test', null, InputOption::VALUE_NONE, 'Si définie, le seul destinataire sera marc@majorclass.fr')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$container = $this->getContainer();
		$em = $container->get('doctrine')->getManager();
		
		$destinataires = $em->getRepository('MajordeskAppBundle:Famille')
					        ->getFamillesWhoseCardExpiresNextMonth();
		
		$nb_destinataires = count($destinataires);
		$output->writeln("Nombre de carte(s) expirant le mois prochain ".$nb_destinataires);
		
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

			$message = \Swift_Message::newInstance()
								->setSubject('Majorclass - Carte arrivant à expiration')
								->setFrom(array('ne-pas-repondre@majorclass.fr' => 'Majorclass'))
								->setTo($mail)
								->setBody($container->get('templating')->render('MajordeskAppBundle:Template:card-expiring-notification.html.twig', array('gender' => $gender, 'nom' => $nom)), 'text/html')
							;
							$container->get('mailer')->send($message);
							$transport = $container->get('swiftmailer.transport.real');						
							$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
			$output->writeln("Mail envoyé à ".$mail);
		}
    }
}