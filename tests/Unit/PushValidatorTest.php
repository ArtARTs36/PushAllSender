<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy\RecipientFriendlyStrategyInterface;
use ArtARTs36\PushAllSender\Validators\PushValidator;
use ArtARTs36\PushAllSender\Validators\Rules\ActionsCountRule;
use ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\PriorityRule;
use ArtARTs36\PushAllSender\Validators\Rules\TitleLengthRule;
use ArtARTs36\PushAllSender\Validators\Rules\TypeRule;
use PHPUnit\Framework\TestCase;

/**
 * Class PushValidatorTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushValidatorTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::__construct
     */
    public function testCreateInstance(): void
    {
        $rulesClasses = [
            MessageLengthRule::class,
            TitleLengthRule::class,
        ];

        $instance = new class ($rulesClasses) extends PushValidator {
            public function getRules()
            {
                return $this->rules;
            }
        };

        self::assertIsArray($rules = $instance->getRules());

        foreach ($rulesClasses as $key => $ruleClass) {
            self::assertInstanceOf($ruleClass, $rules[$key]);
        }
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\MessageLengthRule::isValid
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::validate
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::getLastErrorRule
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
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\PriorityRule::isValid
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::validate
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::getLastErrorRule
     */
    public function testPriorityRule(): void
    {
        $strategy = new class implements RecipientFriendlyStrategyInterface {
            /**
             * @inheritDoc
             */
            public function getPriority(): int
            {
                return 5;
            }
        };

        $push = new Push('Title', 'Message', null, null, $strategy);

        $rule = new PriorityRule();

        $validator = new PushValidator([
            PriorityRule::class,
        ]);

        self::assertFalse($rule->isValid($push));
        self::assertFalse($validator->validate($push));
        self::assertInstanceOf(PriorityRule::class, $validator->getLastErrorRule());

        //

        $push->setMiddlePriority();

        self::assertTrue($rule->isValid($push));
        self::assertTrue($rule->isValid($push));
        self::assertInstanceOf(PriorityRule::class, $validator->getLastErrorRule());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\TypeRule
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::validate
     * @covers \ArtARTs36\PushAllSender\Validators\PushValidator::getLastErrorRule
     */
    public function testTypeRule(): void
    {
        $makePushWithType = function (string $type) {
            return new Push(
                'Title',
                'Message',
                null,
                null,
                null,
                $type,
            );
        };

        $push = $makePushWithType($this->generateString(10));

        $rule = new TypeRule();

        $validator = new PushValidator([
            TypeRule::class,
        ]);

        self::assertFalse($rule->isValid($push));
        self::assertFalse($validator->validate($push));
        self::assertInstanceOf(TypeRule::class, $validator->getLastErrorRule());

        //

        $push = $makePushWithType(Push::TYPE_BROADCAST);

        self::assertTrue($rule->isValid($push));
        self::assertTrue($rule->isValid($push));
        self::assertInstanceOf(TypeRule::class, $validator->getLastErrorRule());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Validators\Rules\ActionsCountRule
     */
    public function testActionCountRule(): void
    {
        $rule = new ActionsCountRule();

        $push = new Push('Title', 'Message');

        $actionParams = ['Title', 'http://site.ru'];

        // 1 action

        $push->additional()->addAction(...$actionParams);

        self::assertTrue($rule->isValid($push));

        // 2 actions

        $push->additional()->addAction(...$actionParams);

        self::assertTrue($rule->isValid($push));

        // 3 actions

        $push->additional()->addAction(...$actionParams);

        self::assertFalse($rule->isValid($push));
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
