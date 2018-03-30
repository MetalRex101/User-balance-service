<?php

namespace App\Validators;

use App\Exceptions\ValidatorException;
use App\Messages\UnblockFunds;

class UnblockFundsValidator extends BaseValidator implements MessageValidator
{

    /**
     * @param UnblockFunds $message
     * @return void
     * @throws ValidatorException
     */
    public function validate($message)
    {
        $this->FundsBlockExists($message->getFundsBlockId());
    }

    /**
     * @param int $funds_block_id
     * @return void
     * @throws ValidatorException
     */
    private function FundsBlockExists(int $funds_block_id)
    {
        $sth = $this->connection->prepare('SELECT * FROM blocked_funds WHERE id = :id');
        $sth->execute([':id' => $funds_block_id]);

        $block = $sth->fetchObject();

        if (!$block) {
            throw new ValidatorException(MessageValidator::FUNDS_BLOCK_NOT_FOUND);
        }
    }
}
