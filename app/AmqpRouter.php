<?php

namespace App;

use App\Exceptions\InvalidMessageStructureException;
use App\Exceptions\MessageException;
use App\MessageHandlers\BlockFundsHandler;
use App\MessageHandlers\EnrolFundsHandler;
use App\MessageHandlers\TransferFundsHandler;
use App\MessageHandlers\UnblockFundsHandler;
use App\MessageHandlers\WithdrawFundsHandler;
use App\Messages\AMessage;
use App\Messages\BlockFundsMessage;
use App\Messages\EnrolFunds;
use App\Messages\TransferFunds;
use App\Messages\UnblockFunds;
use App\Messages\WithdrawFunds;
use App\Validators\BlockFundsValidator;
use App\Validators\EnrolFundsValidator;
use App\Validators\MessageValidator;
use App\Validators\TransferFundsValidator;
use App\Validators\UnblockFundsValidator;
use App\Validators\WithdrawFundsValidator;
use Pimple\Container;

class AmqpRouter
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $message
     * @return AMessage
     * @throws InvalidMessageStructureException
     * @throws MessageException
     */
    public function resolve(array $message): AMessage
    {
        if (empty($message['type'])) {
            throw new InvalidMessageStructureException('missing type');
        }

        $messageObj = $this->getMessage($message);

        $messageObj->setValidator($this->getMessageValidator($message['type']));
        $messageObj->setHandler($this->getMessageHandler($message['type']));

        return $messageObj;
    }

    /**
     * Returns handler for message
     *
     * @param string $type
     * @return mixed
     * @throws MessageException
     */
    private function getMessageHandler(string $type)
    {
        switch ($type) {
            case AMessage::TYPE_ENROL_FUNDS:
                return $this->container[EnrolFundsHandler::class];
            case AMessage::TYPE_WITHDRAW_FUNDS:
                return $this->container[WithdrawFundsHandler::class];
            case AMessage::TYPE_TRANSFER_FUNDS:
                return $this->container[TransferFundsHandler::class];
            case AMessage::TYPE_BLOCK_FUNDS:
                return $this->container[BlockFundsHandler::class];
            case AMessage::TYPE_UNBLOCK_FUNDS:
                return $this->container[UnblockFundsHandler::class];
            default:
                throw new MessageException('handler not found');
        }
    }

    /**
     * Returns validator for message
     *
     * @param string $type
     * @return MessageValidator|null
     */
    private function getMessageValidator(string $type): ?MessageValidator
    {
        switch ($type) {
            case AMessage::TYPE_ENROL_FUNDS:
                return $this->container[EnrolFundsValidator::class];
            case AMessage::TYPE_WITHDRAW_FUNDS:
                return $this->container[WithdrawFundsValidator::class];
            case AMessage::TYPE_TRANSFER_FUNDS:
                return $this->container[TransferFundsValidator::class];
            case AMessage::TYPE_BLOCK_FUNDS:
                return $this->container[BlockFundsValidator::class];
            case AMessage::TYPE_UNBLOCK_FUNDS:
                return $this->container[UnblockFundsValidator::class];
            default:
                return null;
        }
    }

    /**
     * Get message instance by type
     *
     * @param array $message
     * @return AMessage
     * @throws InvalidMessageStructureException
     * @throws MessageException
     */
    private function getMessage(array $message): AMessage
    {
        switch ($message['type']) {
            case AMessage::TYPE_ENROL_FUNDS:
                return new EnrolFunds($message);
            case AMessage::TYPE_WITHDRAW_FUNDS:
                return new WithdrawFunds($message);
            case AMessage::TYPE_TRANSFER_FUNDS:
                return new TransferFunds($message);
            case AMessage::TYPE_BLOCK_FUNDS:
                return new BlockFundsMessage($message);
            case AMessage::TYPE_UNBLOCK_FUNDS:
                return new UnblockFunds($message);
            default:
                throw new InvalidMessageStructureException('unknown type');
        }
    }
}
