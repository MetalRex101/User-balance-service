<?php

namespace App\Validators;

use App\Exceptions\ValidatorException;
use App\Messages\EnrolFunds;

class EnrolFundsValidator extends BaseValidator implements MessageValidator
{
    /**
     * @param EnrolFunds $message
     * @throws ValidatorException
     */
    public function validate($message)
    {
        $this->userExists($message->getUserId());
    }
}
