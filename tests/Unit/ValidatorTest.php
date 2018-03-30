<?php

namespace Tests\Unit;

use App\Exceptions\ValidatorException;
use App\Messages\AMessage;
use App\Messages\EnrolFunds;
use App\Validators\EnrolFundsValidator;
use App\Validators\MessageValidator;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    /** @test */
    public function it_will_raise_validation_exception()
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage(MessageValidator::USER_NOT_FOUND);

        $enrol_funds_message = [
            'type' => AMessage::TYPE_ENROL_FUNDS,
            'payload' => [
                'user_id' => 1,
                'count' => 500
            ]
        ];

        $message = new EnrolFunds($enrol_funds_message);
        /** @var EnrolFundsValidator $validator */
        $validator = $this->container[EnrolFundsValidator::class];

        $validator->validate($message);
    }
}
