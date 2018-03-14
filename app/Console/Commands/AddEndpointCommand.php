<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddEndpointCommand extends Command
{
  protected function configure()
  {
    $this->setName('endpoint:add')
         ->setDescription('Add an endpoint to monitor')
         ->addArgument('endpoint', InputArgument::REQUIRED, 'The endpoint to monitor')
         ->addOption(
           'frequency',
           'f',
           InputOption::VALUE_OPTIONAL,
           'The frequency to check this endpoint, in minutes.',
           1
         );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    dump($input->getOption('frequency'));
    dump($input->getArgument('endpoint'));
  }
}
