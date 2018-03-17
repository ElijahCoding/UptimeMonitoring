<?php

namespace App\Tasks;

use GuzzleHttp\Client;
use App\Scheduler\Task;
use App\Models\Endpoint;
use App\Events\EndpointIsDown;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PingEndpoint extends Task
{
  protected $client;
  protected $endpoint;
  protected $dispatcher;

  public function __construct(Endpoint $endpoint, Client $client, EventDispatcher $dispatcher)
  {
    $this->client = $client;
    $this->endpoint = $endpoint;
    $this->dispatcher = $dispatcher;
  }

  public function handle()
  {
    try {
      $response = $this->client->request('GET', $this->endpoint->uri);
    } catch (RequestException $e) {
      $response = $e->getResponse();
    }

    $this->endpoint->statuses()->create([
      'status_code' => $response->getStatusCode()
    ]);

    $this->dispatchEvents();
  }

  protected function dispatchEvents()
  {
    if ($this->endpoint->status->isDown()) {
      $this->dispatcher->dispatch(EndpointIsDown::NAME, new EndpointIsDown($this->endpoint));
    }
  }
}
