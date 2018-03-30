<?php

namespace App\Messages;

use App\Exceptions\InvalidMessageStructureException;

class WithdrawFunds extends AMessage
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $count;

    protected $hasPayload = true;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param array $payload
     * @throws InvalidMessageStructureException
     */
    protected function setPayload(array $payload)
    {
        $userId = $payload['user_id'] ?? null;
        $count = $payload['count'] ?? null;

        if (!$userId) {
            throw new InvalidMessageStructureException('user_id is missing');
        }

        if (!\is_int($userId)) {
            throw new InvalidMessageStructureException('invalid user_id type');
        }

        if (!$count) {
            throw new InvalidMessageStructureException('count is missing');
        }

        if (!\is_int($count)) {
            throw new InvalidMessageStructureException('invalid user_id type');
        }

        $this->userId = $userId;
        $this->count = $count;
    }
}
