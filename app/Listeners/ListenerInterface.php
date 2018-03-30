<?php

namespace App\Listeners;

use App\Events\Event;

interface ListenerInterface
{
    public function handle(Event $event);
}
