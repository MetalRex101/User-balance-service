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
     * Validate user existence id database
     *
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
     * Validate count equals or greater than 0
     *
     * @param int|null $count
     * @throws ValidatorException
     */
    public function validateCount(int $count)
    {
        if ($count <= 0) {
            throw new ValidatorException(MessageValidator::COUNT_NOT_DEFINED);
        }
    }
}
