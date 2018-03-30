<?php

namespace App\MessageHandlers;

use App\Exceptions\NotEnoughFundsException;
use App\Messages\TransferFunds;

class TransferFundsHandler extends BaseHandler implements MessageHandler
{

    /**
     * @param TransferFunds $message
     * @return void
     * @throws NotEnoughFundsException
     */
    public function handle($message)
    {
        $this->userService->transferFunds($message->getUserFromId(), $message->getUserToId(), $message->getCount());
    }
}
