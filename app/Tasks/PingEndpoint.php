<?php

namespace App\Tasks;

use GuzzleHttp\Client;
use App\Models\Endpoint;
use GuzzleHttp\Exception\RequestException;

class PingEndpoint
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
      dump($e->getResponse()->getStatusCode());
    }

  }
}
