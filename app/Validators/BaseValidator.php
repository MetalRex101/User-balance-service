<?php

namespace App\Validators;

use App\Exceptions\ValidatorException;
use App\Repositories\UserRepository;
use PDO;

class BaseValidator
{
    /**
     * @var PDO
     */
    protected $connection;
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(PDO $connection, UserRepository $userRepository)
    {
        $this->connection = $connection;
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @throws ValidatorException
     */
    public function userExists(int $userId)
    {
        $user = $this->userRepository->getUserById($userId);

        if (null === $user) {
            throw new ValidatorException(MessageValidator::USER_NOT_FOUND);
        }
    }

    /**
     * @param int|null $count
     * @throws ValidatorException
     */
    public function validateCount(?int $count)
    {
        if (!$count) {
            throw new ValidatorException(MessageValidator::COUNT_NOT_DEFINED);
        }
    }
}
