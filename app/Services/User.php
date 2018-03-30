<?php

namespace App\Services;

use App\Exceptions\NotEnoughFundsException;
use App\Repositories\UserRepository;
use PDO;

class User
{
    /**
     * @var \PDO
     */
    private $connection;
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(PDO $connection, UserRepository $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
    }

    /**
     * Enrol funds to user
     *
     * @param int $id
     * @param int $count
     */
    public function enrollFunds(int $id, int $count): void
    {
        $sth = $this->connection->prepare('UPDATE users SET `funds` = (`funds` + :count) where id = :id');
        $sth->execute(['count' => $count, ':id' => $id]);
    }

    /**
     * withdraw funds from user
     *
     * @param int $id
     * @param int $count
     * @throws NotEnoughFundsException
     */
    public function withdrawFunds(int $id, int $count): void
    {
        if (!$this->hasEnoughFunds($id, $count)) {
            throw new NotEnoughFundsException('not enough funds to withdraw');
        }

        $sth = $this->connection->prepare('UPDATE users SET `funds` = (`funds` - :count) where id = :id');
        $sth->execute(['count' => $count, ':id' => $id]);
    }

    /**
     * Returns true if user has enough funds
     *
     * @param int $id
     * @param int $count
     * @return bool
     */
    public function hasEnoughFunds(int $id, int $count): bool
    {
        $sth = $this->connection->prepare('Select funds from users where id = :id');
        $sth->execute([':id' => $id]);
        $record = $sth->fetchObject();

        return $record->funds >= $count;
    }

    /**
     * Transfers funds from one user to another
     *
     * @param int $fromId
     * @param int $toId
     * @param int $count
     * @throws NotEnoughFundsException
     */
    public function transferFunds(int $fromId, int $toId, int $count): void
    {
        $this->connection->beginTransaction();

        try {
            $this->withdrawFunds($fromId, $count);
        } catch (NotEnoughFundsException $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->enrollFunds($toId, $count);

        $this->connection->commit();
    }

    /**
     * Block user funds
     *
     * @param int $id
     * @param int $count
     * @throws NotEnoughFundsException
     */
    public function blockFunds(int $id, int $count): void
    {
        $this->connection->beginTransaction();

        try {
            $this->withdrawFunds($id, $count);

            $sth = $this->connection->prepare(
                'INSERT INTO blocked_funds (user_from_id, funds) VALUES (:id, :count)'
            );

            $sth->execute([':id' => $id, ':count' => $count]);
        } catch (NotEnoughFundsException $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->connection->commit();
    }

    /**
     * Block user funds
     *
     * @param int $blockedFundsId
     * @throws \PDOException
     */
    public function unblockFunds(int $blockedFundsId): void
    {
        $this->connection->beginTransaction();

        try {
            $sth = $this->connection->prepare('SELECT * from blocked_funds where id = :id');
            $sth->execute([':id' => $blockedFundsId]);
            $record = $sth->fetchObject();

            $this->enrollFunds($record->user_from_id, $record->funds);

            $sth = $this->connection->prepare('DELETE from blocked_funds where id = :id');
            $sth->execute([':id' => $blockedFundsId]);
        } catch (\PDOException $e) {
            $this->connection->rollBack();

            throw $e;
        }

        $this->connection->commit();
    }
}
