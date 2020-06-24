<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Interfaces\PushRecipientInterface;
use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy\NightMinPriorityStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Class PushTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Push::setMaxPriority
     */
    public function testSetMaxPriority(): void
    {
        $push = $this->makePush()->setMaxPriority();

        self::assertEquals(Push::PRIORITY_MAX, $push->getPriority());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Push::setMiddlePriority
     */
    public function testSetMiddlePriority(): void
    {
        $push = $this->makePush()->setMiddlePriority();

        self::assertEquals(Push::PRIORITY_MIDDLE, $push->getPriority());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Push::setMinPriority
     */
    public function testSetMinPriority(): void
    {
        $push = $this->makePush()->setMinPriority();

        self::assertEquals(Push::PRIORITY_MIN, $push->getPriority());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Push::getRecipientId
     */
    public function testGetRecipientId(): void
    {
        $push = $this->makePush();

        self::assertNull($push->getRecipientId());

        //

        $recipient = new class implements PushRecipientInterface {
            /**
             * @inheritDoc
             */
            public function getPushAllId(): int
            {
                return 5;
            }
        };

        $push = new Push('Title', 'Message', $recipient);

        self::assertEquals(5, $push->getRecipientId());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Push::getPriority
     */
    public function testGetPriority(): void
    {
        $defaultStrategy = new NightMinPriorityStrategy();

        $push = $this->makePush();

        self::assertEquals($defaultStrategy->getPriority(), $push->getPriority());
    }

    /**
     * @return Push
     */
    protected function makePush(): Push
    {
        return new Push('Title', 'Message');
    }
}
