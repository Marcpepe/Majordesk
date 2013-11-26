<?php

namespace Majordesk\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class AtosReceiveCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('atos:receive')
            ->setDescription('Reception depuis les serveurs d\'Atos et mise a jour de la BDD.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		// Préparation
        $command = $this->getApplication()->find('atos:receiving:receive');
		$arguments = array(
			'command' => 'atos:receiving:receive',
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);
		
		// Envoi
		$command = $this->getApplication()->find('atos:receiving:update');
		$arguments = array(
			'command' => 'atos:receiving:update',
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);
    }
}