<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class AtosSendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:send')
            ->setDescription('Préparation et envoi vers Atos')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		// Préparation
        $command = $this->getApplication()->find('atos:sending:prepare');
		$arguments = array(
			'command' => 'atos:sending:prepare',
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);
		
		// Envoi
		$command = $this->getApplication()->find('atos:sending:send');
		$arguments = array(
			'command' => 'atos:sending:send',
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);
    }
}