<?php

namespace App\Listeners;

use App\Events\Event;

class MessageHandledListener implements ListenerInterface
{
    public function __construct()
    {
    }

    /**
     * @param Event $event
     */
    public function handle(Event $event)
    {
        // handle event
    }
}
