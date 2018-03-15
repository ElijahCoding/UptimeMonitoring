<?php

namespace App\Console\Commands;

use App\Models\Endpoint;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
  protected function configure()
  {
    $this->setName('status')
         ->setDescription('Status of all endpoints.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $endpoints = Endpoint::with('statuses')->get();

    $table = new Table($output);

    $table->setHeaders(['ID', 'URI', 'Frequency', 'Last checked', 'Status', 'Response code'])
          ->setRows(
            $endpoints->map(function ($endpoint) {
              return array_merge(
                $endpoint->only(['id', 'uri', 'frequency']),
                $this->getEnpointStatus($endpoint)
              );
            })->toArray()
          );

    $table->render();
  }

  protected function getEnpointStatus(Endpoint $endpoint)
  {
    return [
      'created_at' => $endpoint->status->created_at,
      'status' => 'up',
      'status_code' => $endpoint->status->status_code
    ];
  }
}
