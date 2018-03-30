<?php

namespace App\MessageHandlers;

use App\Exceptions\NotEnoughFundsException;
use App\Messages\BlockFundsMessage;

class BlockFundsHandler extends BaseHandler implements MessageHandler
{

    /**
     * @param BlockFundsMessage $message
     * @return void
     * @throws NotEnoughFundsException
     */
    public function handle($message)
    {
        $this->userService->blockFunds($message->getUserId(), $message->getCount());
    }
}
