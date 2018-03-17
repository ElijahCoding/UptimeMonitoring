<?php

namespace App\Listeners;

use Symfony\Component\EventDispatcher\Event;

class EndpointDownSMSNotification
{
  public function handle(Event $event)
  {
    dump('endpoint down listener');
  }
}
