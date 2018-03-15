<?php

namespace App\Tasks;

use GuzzleHttp\Client;
use App\Scheduler\Task;
use App\Models\Endpoint;
use GuzzleHttp\Exception\RequestException;

class PingEndpoint extends Task
{
  protected $client;
  protected $endpoint;

  public function __construct(Endpoint $endpoint, Client $client)
  {
    $this->client = $client;
    $this->endpoint = $endpoint;
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
  }
}
