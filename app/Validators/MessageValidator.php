<?php

namespace App\Validators;

use App\Messages\AMessage;

interface MessageValidator
{
    public const USER_NOT_FOUND = 'user not found';
    public const COUNT_NOT_DEFINED = 'count not defined or equals to 0';
    public const FUNDS_BLOCK_NOT_FOUND = 'funds block not found';

    /**
     * @param AMessage $message
     * @return void
     */
    public function validate($message);
}
