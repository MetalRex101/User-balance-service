<?php

namespace App;

use App\Events\MessageHandled;
use App\Exceptions\Handler;
use App\Services\EventEmitter;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Pimple\Container;

class App
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var AmqpRouter
     */
    private $router;

    /**
     * @var Handler
     */
    private $exceptionHandler;

    /**
     * @var EventEmitter
     */
    private $eventEmitter;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->exceptionHandler = $container[Handler::class];
        $this->router = new AmqpRouter($this->container);
        $this->eventEmitter = $container[EventEmitter::class];
    }

    /**
     * Run the app
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     */
    public function run(): void
    {
        // TODO move values to config
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // TODO move values to config
        $channel->queue_declare('user_balance', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        /**
         * @param AMQPMessage $msg
         */
        $callback = function (AMQPMessage $msg) {
            try {
                $message = $this->router->resolve(json_decode($msg->getBody(), true));
                $message->validate();
                $message->handle();
            } catch (Exception $exception) {
                $this->exceptionHandler->handle($exception);
            }

            $this->eventEmitter->emit(new MessageHandled());
        };

        // TODO move values to config
        $channel->basic_consume('user_balance', '', false, true, false, false, $callback);

        while (\count($channel->callbacks)) {
            $channel->wait();
        }

        // TODO grace full shutdown
        $channel->close();
        $connection->close();
    }
}
