<?php

namespace App\Validators;

use App\Exceptions\ValidatorException;
use App\Messages\BlockFundsMessage;

class BlockFundsValidator extends BaseValidator implements MessageValidator
{

    /**
     * @param BlockFundsMessage $message
     * @return void
     * @throws ValidatorException
     */
    public function validate($message)
    {
        $this->userExists($message->getUserId());
        $this->validateCount($message->getCount());
    }
}
