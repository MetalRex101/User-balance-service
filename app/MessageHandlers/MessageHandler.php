<?php

namespace App\MessageHandlers;

interface MessageHandler
{
    /**
     * @param $message
     * @return mixed
     */
    public function handle($message);
}
