<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Support\Hour;
use PHPUnit\Framework\TestCase;

/**
 * Class HourTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class HourTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Support\Hour::setTest
     */
    public function testSetTest(): void
    {
        $hour = 22;

        Hour::setTest($hour);

        self::assertEquals($hour, Hour::get());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Support\Hour::clearTest
     */
    public function testClearTest(): void
    {
        $hour = 11;

        Hour::setTest($hour);
        Hour::clearTest();

        self::assertNotEquals($hour, Hour::get());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Support\Hour::get
     */
    public function testGetRealHour(): void
    {
        $hour = (int) date('H');

        self::assertEquals($hour, Hour::get());
    }
}
