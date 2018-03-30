<?php

namespace App\Messages;

use App\Exceptions\InvalidMessageStructureException;

class UnblockFunds extends AMessage
{
    /**
     * @var bool
     */
    protected $hasPayload = true;

    /**
     * @var int
     */
    private $funds_block_id;

    /**
     * @return int
     */
    public function getFundsBlockId(): int
    {
        return $this->funds_block_id;
    }

    /**
     * @param array $payload
     * @throws InvalidMessageStructureException
     */
    protected function setPayload(array $payload)
    {
        // TODO move validation logic out
        $funds_block_id = $payload['funds_block_id'] ?? null;

        if (!$funds_block_id) {
            throw new InvalidMessageStructureException('funds_block_id is missing');
        }

        if (!\is_int($funds_block_id)) {
            throw new InvalidMessageStructureException('invalid funds_block_id type');
        }

        $this->funds_block_id = $funds_block_id;
    }
}
