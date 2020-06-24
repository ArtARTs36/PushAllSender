<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Validators\PushValidator;
use ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\TitleLengthRule;
use PHPUnit\Framework\TestCase;

/**
 * Class PushValidatorTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushValidatorTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule::isValid
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::validate
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::getLastErrorRule
     * @throws \ArtARTs36\PushAllSender\Exceptions\PushException
     */
    public function testMessageLengthRule(): void
    {
        $push = new Push(
            'Title',
            $this->generateString(501),
        );

        $validator = new PushValidator([
            MessageLengthRule::class,
        ]);

        $rule = new MessageLengthRule();

        //

        self::assertFalse($rule->isValid($push));
        self::assertFalse($validator->validate($push));
        self::assertInstanceOf(MessageLengthRule::class, $validator->getLastErrorRule());

        //

        $push->message = 'Push with correct Message Length';

        self::assertTrue($rule->isValid($push));
        self::assertTrue($validator->validate($push));
        self::assertInstanceOf(MessageLengthRule::class, $validator->getLastErrorRule());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\TitleLengthRule::isValid
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::validate
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::getLastErrorRule
     */
    public function testTitleLengthRule(): void
    {
        $push = new Push(
            $this->generateString(101),
            'Message',
        );

        $validator = new PushValidator([
            TitleLengthRule::class,
        ]);

        $rule = new TitleLengthRule();

        //

        self::assertFalse($rule->isValid($push));
        self::assertFalse($validator->validate($push));
        self::assertInstanceOf(TitleLengthRule::class, $validator->getLastErrorRule());

        //

        $push->title = 'Push with correct Title Length';

        self::assertTrue($rule->isValid($push));
        self::assertTrue($validator->validate($push));
        self::assertInstanceOf(TitleLengthRule::class, $validator->getLastErrorRule());
    }

    /**
     * @param int $length
     * @return string
     */
    protected function generateString(int $length): string
    {
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $charsCount = mb_strlen($chars) - 1;
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $charsCount)];
        }

        return (string) $string;
    }
}
