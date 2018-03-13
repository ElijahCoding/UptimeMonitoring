<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddEndpointCommand extends Command
{
  protected function configure()
  {
    $this->setName('endpoint:add')
         ->setDescription('Add an endpoint to monitor');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln('<info>works</info>');
  }
}
