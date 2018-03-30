<?php

namespace App\Exceptions;

use Exception;

class Handler
{
    /**
     * Handle exception
     *
     * @param Exception $exception
     */
    public function handle(Exception $exception)
    {
        // TODO file logger
        echo sprintf('unhandled exception: %s' . PHP_EOL, $exception->getMessage());
    }
}
