<?php

namespace App\MessageHandlers;

use App\Messages\UnblockFunds;

class UnblockFundsHandler extends BaseHandler implements MessageHandler
{

    /**
     * @param UnblockFunds $message
     * @return void
     * @throws \PDOException
     */
    public function handle($message)
    {
        $this->userService->unblockFunds($message->getFundsBlockId());
    }
}
