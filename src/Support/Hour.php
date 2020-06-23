<?php

namespace ArtARTs36\PushAllSender\Support;

/**
 * Class Hour
 * @package ArtARTs36\PushAllSender\Support
 */
class Hour
{
    /**
     * @var int|null
     */
    protected static $testHour = null;

    /**
     * @param int $hour
     */
    public static function setTest(int $hour): void
    {
        static::$testHour = $hour;
    }

    /**
     * Get test or real Hour
     *
     * @return int
     */
    public static function get(): int
    {
        return static::$testHour ?? (int) date('H');
    }

    /**
     * Clear test Hour
     */
    public static function clearTest(): void
    {
        static::$testHour = null;
    }
}
