<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('majordesk:mail:test')
            ->setDescription('Envoi un mail afin de tester son template')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$container = $this->getContainer();
		$em = $container->get('doctrine')->getManager();
		
		// Param
		// $gender = '';
		
		$message = \Swift_Message::newInstance()
				->setSubject('Mail essai')
				->setFrom(array('test@majorclass.fr'=>'Majorclass'))
				->setTo(array('marc@majorclass.fr','marc.perrinpelletier@gmail.com'))
				// ->setBody($container->get('templating')->render('MajordeskAppBundle:Template:bienvenue.html.twig', array('gender' => $gender, 'nom' => $nom, 'liste' => $liste)), 'text/html')
				->setBody($container->get('templating')->render('MajordeskAppBundle:Template:bienvenue.html.twig'), 'text/html')
			;
			$container->get('mailer')->send($message);
			$transport = $container->get('swiftmailer.transport.real');						
			$container->get('mailer')->getTransport()->getSpool()->flushQueue($transport);
		$output->writeln("Test mail : OK");
    }
}