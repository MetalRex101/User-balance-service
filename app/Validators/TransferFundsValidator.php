<?php

namespace App\Validators;

use App\Messages\TransferFunds;

class TransferFundsValidator extends BaseValidator implements MessageValidator
{

    /**
     * @param TransferFunds $message
     * @return void
     * @throws \App\Exceptions\ValidatorException
     */
    public function validate($message)
    {
        $this->userExists($message->getUserFromId());
        $this->userExists($message->getUserToId());
        $this->validateCount($message->getCount());
    }
}
