<?php

namespace Tests\Unit;

use App\Exceptions\NotEnoughFundsException;
use App\Services\User;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /** @test */
    public function it_will_increase_user_funds_count()
    {
        $this->withoutCommit(function () {
            /** @var User $userService */
            $userService = $this->container[User::class];
            $count = 500;

            $userId = $this->createUser(null, $count);
            $increaseCount = 500;

            $userService->enrollFunds($userId, $increaseCount);

            $user = $this->getUserById($userId);
            $this->assertNotNull($user);

            $this->assertEquals($user->funds, $increaseCount + $count);
        });
    }

    /** @test */
    public function it_will_reduce_user_funds_count()
    {
        $this->withoutCommit(function () {
            /** @var User $userService */
            $userService = $this->container[User::class];
            $count = 500;

            $userId = $this->createUser(null, $count);
            $reduceCount = 450;

            $userService->withdrawFunds($userId, $reduceCount);

            $user = $this->getUserById($userId);
            $this->assertNotNull($user);

            $this->assertEquals($user->funds, $count - $reduceCount);
        });
    }

    /** @test */
    public function it_will_raise_exception_when_points_not_enough_to_reduce()
    {
        $this->expectException(NotEnoughFundsException::class);

        $this->withoutCommit(function () {
            /** @var User $userService */
            $userService = $this->container[User::class];
            $count = 500;

            $userId = $this->createUser(null, $count);
            $reduceCount = 550;

            $userService->withdrawFunds($userId, $reduceCount);
        });
    }

    /** @test */
    public function it_will_return_true_when_enough_funds()
    {
        $this->withoutCommit(function () {
            /** @var User $userService */
            $userService = $this->container[User::class];
            $count = 500;
            $reduceCount = 450;

            $userId = $this->createUser(null, $count);

            $this->assertTrue($userService->hasEnoughFunds($userId, $reduceCount));
        });
    }

    /** @test */
    public function it_will_transfer_funds_from_one_user_to_another()
    {
        /** @var User $userService */
        $userService = $this->container[User::class];
        $user1_balance = 500;
        $user2_balance = 0;
        $reduceCount = 450;

        $userFromId = $this->createUser(null, $user1_balance);
        $userToId = $this->createUser(null, $user2_balance);

        $userService->transferFunds($userFromId, $userToId, $reduceCount);

        $userFrom = $this->getUserById($userFromId);
        $userTo = $this->getUserById($userToId);

        $this->assertNotNull($userFrom);
        $this->assertNotNull($userTo);

        $this->assertEquals($userFrom->funds, $user1_balance - $reduceCount);
        $this->assertEquals($userTo->funds, $user2_balance + $reduceCount);
    }

    /** @test */
    public function it_will_block_user_funds()
    {
        /** @var User $userService */
        $userService = $this->container[User::class];
        $user_balance = 500;
        $funds_to_block = 450;

        $this->createUser(null, $user_balance);

        $userId = $this->createUser(null, $user_balance);
        $userService->blockFunds($userId, $funds_to_block);

        $user = $this->getUserById($userId);
        $this->assertNotNull($user);

        $this->assertEquals($user->funds, $user_balance - $funds_to_block);

        $sth = $this->connection->prepare('SELECT * FROM blocked_funds where user_from_id = :user_id');
        $sth->execute([':user_id' => $userId]);

        $blocked_fund = $sth->fetchObject() ?: null;

        $this->assertNotNull($blocked_fund);
        $this->assertEquals($blocked_fund->funds, $funds_to_block);
    }

    /** @test */
    public function it_will_unblock_user_funds()
    {
        /** @var User $userService */
        $userService = $this->container[User::class];
        $user_balance = 150;
        $blocked_funds = 500;

        $this->createUser(null, $user_balance);

        $userId = $this->createUser(null, $user_balance);
        $blocked_funds_id = $this->createBlockedFundsRecord($userId, $blocked_funds);
        $userService->unblockFunds($blocked_funds_id);

        $user = $this->getUserById($userId);
        $this->assertNotNull($user);

        $this->assertEquals($user->funds, $user_balance + $blocked_funds);

        $sth = $this->connection->prepare('SELECT * FROM blocked_funds where id = :id');
        $sth->execute([':id' => $blocked_funds_id]);

        $blocked_funds_record = $sth->fetchObject() ?: null;
        $this->assertNull($blocked_funds_record);
    }

    /**
     * @param null $name
     * @param int $funds
     * @return string
     * @throws \Exception
     */
    private function createUser($name = null, int $funds = null)
    {
        if (null === $name) {
            $name = bin2hex(random_bytes(16));
        }

        if (null === $funds) {
            $funds = random_int(500, 10000);
        }

        $sth = $this->connection->prepare('INSERT INTO USERS (name, funds) VALUES (:name, :funds)');
        $sth->execute([':name' => $name, ':funds' => $funds]);

        return $this->connection->lastInsertId();
    }

    /**
     * Creates blocked_funds record for given user
     *
     * @param int $userId
     * @param int|null $funds
     * @return string
     */
    private function createBlockedFundsRecord(int $userId, int $funds = null)
    {
        $sth = $this->connection->prepare('INSERT INTO blocked_funds (user_from_id, funds) VALUES (:user_id, :funds)');
        $sth->execute([':user_id' => $userId, ':funds' => $funds]);

        return $this->connection->lastInsertId();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    private function getUserById(int $userId)
    {
        $sth = $this->connection->prepare('SELECT funds from users where id = :id');
        $sth->execute([':id' => $userId]);

        return $sth->fetchObject() ?: null;
    }
}
