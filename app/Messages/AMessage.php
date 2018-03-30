<?php

namespace App\Messages;

use App\Exceptions\InvalidMessageStructureException;
use App\Exceptions\MessageException;
use App\MessageHandlers\MessageHandler;
use App\Validators\MessageValidator;

abstract class AMessage
{
    public const TYPE_ENROL_FUNDS = 'enrol_funds';
    public const TYPE_WITHDRAW_FUNDS = 'withdraw_funds';
    public const TYPE_TRANSFER_FUNDS = 'transfer_funds';
    public const TYPE_BLOCK_FUNDS = 'block_funds';
    public const TYPE_UNBLOCK_FUNDS = 'unblock_funds';

    public const TYPES = [
        self::TYPE_ENROL_FUNDS,
        self::TYPE_WITHDRAW_FUNDS,
        self::TYPE_TRANSFER_FUNDS,
        self::TYPE_BLOCK_FUNDS,
        self::TYPE_UNBLOCK_FUNDS,
    ];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var MessageHandler
     */
    protected $handler;

    /**
     * @var MessageValidator
     */
    protected $validator;

    /**
     * @var bool
     */
    protected $hasPayload = false;

    /**
     * BaseMessage constructor.
     * @param array $data
     * @throws InvalidMessageStructureException
     * @throws MessageException
     */
    public function __construct(array $data)
    {
        $this->type = $data['type'] ?? null;

        if (!$this->type) {
            throw new MessageException('invalid message structure');
        }

        if ($this->hasPayload && !isset($data['payload'])) {
            throw new InvalidMessageStructureException('payload missing');
        }

        $this->setPayload($data['payload']);
    }

    /**
     * @param MessageHandler $handler
     */
    public function setHandler(MessageHandler $handler): void
    {
        $this->handler = $handler;
    }

    public function setValidator(?MessageValidator $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * If validator set, validate message
     */
    public function validate()
    {
        if (null !== $this->validator) {
            $this->validator->validate($this);
        }
    }

    /**
     * Handle message operation
     *
     * @throws MessageException
     */
    public function handle()
    {
        if (null === $this->handler) {
            throw new MessageException('missing message handler');
        }

        $this->handler->handle($this);
    }

    abstract protected function setPayload(array $payload);
}
