<?php

namespace App\Events;

use App\Models\Endpoint;
use Symfony\Component\EventDispatcher\Event;

class EndpointIsDown extends Event
{
  const NAME = 'endpoint.down';

  protected $endpoint;

  public function __construct(Endpoint $endpoint)
  {
    $this->endpoint = $endpoint;
  }
}
