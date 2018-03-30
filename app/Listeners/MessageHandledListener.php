<?php

namespace App\Listeners;

use App\Events\Event;

class MessageHandledListener implements ListenerInterface
{
    public function __construct()
    {
    }

    public function handle(Event $event)
    {
        // handle event
    }
}
