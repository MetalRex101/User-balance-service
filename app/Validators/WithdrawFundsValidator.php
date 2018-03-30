<?php

namespace App\Validators;

use App\Exceptions\ValidatorException;
use App\Messages\WithdrawFunds;

class WithdrawFundsValidator extends BaseValidator implements MessageValidator
{

    /**
     * @param WithdrawFunds $message
     * @throws ValidatorException
     */
    public function validate($message)
    {
        $this->userExists($message->getUserId());
    }
}
