<?php

namespace App\Console\Commands;

use App\Models\Endpoint;
use App\Tasks\PingEndpoint;
use App\Scheduler\Kernel;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
  protected $client;

  public function __construct(Client $client)
  {
    $this->client = $client;

    parent::__construct();
  }

  protected function configure()
  {
    $this->setName('run');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $kernel = new Kernel;

    $endpoints = Endpoint::get();

    foreach ($endpoints as $endpoint) {
      $kernel->add(
        new PingEndpoint($endpoint, $this->client)
      )->everyMinutes($endpoint->frequency);
    }

    $kernel->run();
  }
}
