<?php

namespace App\MessageHandlers;

use App\Messages\WithdrawFunds;

class WithdrawFundsHandler extends BaseHandler implements MessageHandler
{

    /**
     * @param WithdrawFunds $message
     * @return void
     * @throws \App\Exceptions\NotEnoughFundsException
     */
    public function handle($message)
    {
        $this->userService->withdrawFunds($message->getUserId(), $message->getCount());
    }
}
