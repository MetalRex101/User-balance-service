<?php

namespace App\MessageHandlers;

interface MessageHandler
{
    /**
     * Handle message operation
     *
     * @param $message
     * @return mixed
     */
    public function handle($message);
}
