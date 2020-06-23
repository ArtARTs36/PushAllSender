<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy\NightMinPriorityStrategy;
use ArtARTs36\PushAllSender\Support\Hour;
use PHPUnit\Framework\TestCase;

/**
 * Class NightMinPriorityStrategyTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class NightMinPriorityStrategyTest extends TestCase
{
    public function test(): void
    {
        $hour = 22;

        Hour::setTest($hour);

        $strategy = new NightMinPriorityStrategy();

        self::assertEquals(Push::PRIORITY_MIN, $strategy->getPriority());

        //

        $hour = 5;

        Hour::setTest($hour);

        self::assertEquals(Push::PRIORITY_MIN, $strategy->getPriority());

        //

        $hour = 12;

        Hour::setTest($hour);

        self::assertEquals(Push::PRIORITY_MIDDLE, $strategy->getPriority());
    }
}
