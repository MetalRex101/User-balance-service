<?php

namespace App\MessageHandlers;

use App\Messages\EnrolFunds;

class EnrolFundsHandler extends BaseHandler implements MessageHandler
{

    /**
     * @param EnrolFunds $message
     * @return void
     */
    public function handle($message)
    {
        $this->userService->enrollFunds($message->getUserId(), $message->getCount());
    }
}
