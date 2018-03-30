<?php

namespace App\Messages;

use App\Exceptions\InvalidMessageStructureException;

class TransferFunds extends AMessage
{
    /**
     * @var bool
     */
    protected $hasPayload = true;

    /**
     * @var int
     */
    private $user_from_id;

    /**
     * @var int
     */
    private $user_to_id;

    /**
     * @var int
     */
    private $count;

    /**
     * @param array $payload
     * @throws InvalidMessageStructureException
     */
    protected function setPayload(array $payload)
    {
        // TODO move validation logic out
        $user_from_id = $payload['user_from_id'] ?? null;
        $user_to_id = $payload['user_to_id'] ?? null;
        $count = $payload['count'] ?? null;

        if (!$user_from_id) {
            throw new InvalidMessageStructureException('user_from_id is missing');
        }

        if (!\is_int($user_from_id)) {
            throw new InvalidMessageStructureException('invalid user_from_id type');
        }

        if (!$user_to_id) {
            throw new InvalidMessageStructureException('user_to_id is missing');
        }

        if (!\is_int($user_to_id)) {
            throw new InvalidMessageStructureException('invalid user_to_id type');
        }

        if (!$count) {
            throw new InvalidMessageStructureException('count is missing');
        }

        if (!\is_int($count)) {
            throw new InvalidMessageStructureException('invalid user_id type');
        }

        $this->user_from_id = $user_from_id;
        $this->user_to_id = $user_to_id;
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getUserFromId(): int
    {
        return $this->user_from_id;
    }

    /**
     * @return int
     */
    public function getUserToId(): int
    {
        return $this->user_to_id;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
