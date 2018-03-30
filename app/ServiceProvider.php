<?php

namespace App;

use App\Exceptions\Handler;
use App\Listeners\MessageHandledListener;
use App\MessageHandlers\BlockFundsHandler;
use App\MessageHandlers\EnrolFundsHandler;
use App\MessageHandlers\TransferFundsHandler;
use App\MessageHandlers\UnblockFundsHandler;
use App\MessageHandlers\WithdrawFundsHandler;
use App\Repositories\UserRepository;
use App\Services\Config;
use App\Services\EventEmitter;
use App\Services\User;
use App\Validators\BlockFundsValidator;
use App\Validators\EnrolFundsValidator;
use App\Validators\TransferFundsValidator;
use App\Validators\UnblockFundsValidator;
use App\Validators\WithdrawFundsValidator;
use PDO;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registering services
     *
     * @param Container $pimple
     */
    public function register(Container $pimple): void
    {
        $this->PDOConnection($pimple);

        $pimple[Config::class] = function () {
            return new Config();
        };

        $this->messageHandlers($pimple);

        $pimple[Handler::class] = function () {
            return new Handler();
        };

        $pimple[UserRepository::class] = function () use ($pimple) {
            return new UserRepository($pimple['PDOConnection']);
        };

        $this->messageValidators($pimple);

        $pimple[User::class] = function () use ($pimple) {
            return new User($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };

        $this->listeners($pimple);

        $pimple[EventEmitter::class] = function () use ($pimple) {
            return new EventEmitter($pimple);
        };
    }

    private function PDOConnection(Container $pimple): void
    {
        $pimple['PDOConnection'] = function () use ($pimple) {
            $connection = $pimple[Config::class]->getPdoConnection();
            $pdo = new PDO($connection['dsn'], $connection['user'], $connection['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        };
    }

    /**
     * @param Container $pimple
     */
    private function messageHandlers(Container $pimple): void
    {
        $pimple[EnrolFundsHandler::class] = function () use ($pimple) {
            return new EnrolFundsHandler($pimple[User::class]);
        };

        $pimple[WithdrawFundsHandler::class] = function () use ($pimple) {
            return new WithdrawFundsHandler($pimple[User::class]);
        };

        $pimple[TransferFundsHandler::class] = function () use ($pimple) {
            return new TransferFundsHandler($pimple[User::class]);
        };

        $pimple[BlockFundsHandler::class] = function () use ($pimple) {
            return new BlockFundsHandler($pimple[User::class]);
        };

        $pimple[UnblockFundsHandler::class] = function () use ($pimple) {
            return new UnblockFundsHandler($pimple[User::class]);
        };
    }

    /**
     * @param Container $pimple
     */
    private function messageValidators(Container $pimple): void
    {
        $pimple[EnrolFundsValidator::class] = function () use ($pimple) {
            return new EnrolFundsValidator($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };

        $pimple[WithdrawFundsValidator::class] = function () use ($pimple) {
            return new WithdrawFundsValidator($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };

        $pimple[TransferFundsValidator::class] = function () use ($pimple) {
            return new TransferFundsValidator($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };

        $pimple[BlockFundsValidator::class] = function () use ($pimple) {
            return new BlockFundsValidator($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };

        $pimple[UnblockFundsValidator::class] = function () use ($pimple) {
            return new UnblockFundsValidator($pimple['PDOConnection'], $pimple[UserRepository::class]);
        };
    }

    /**
     * @param Container $pimple
     */
    private function listeners(Container $pimple): void
    {
        $pimple[MessageHandledListener::class] = function () use ($pimple) {
            return new MessageHandledListener();
        };
    }
}
