<?php

namespace App\Services;

use App\Events\Event;
use App\Events\MessageHandled;
use App\Listeners\MessageHandledListener;
use Pimple\Container;

class EventEmitter
{
    /**
     * Register here listeners for events
     *
     * @var array
     */
    private $registered = [
        MessageHandled::class => [
            MessageHandledListener::class
        ]
    ];

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Emit Event
     *
     * @param Event $event
     */
    public function emit(Event $event)
    {
        $listeners = $this->registered[\get_class($event)] ?? null;

        if (null !== $listeners) {
            foreach ($listeners as $listener) {
                $this->container[$listener]->handle($event);
            }
        }
    }
}
