<?php

namespace ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy;

use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Support\Hour;

/**
 * Class NightMinPriorityStrategy
 * @package ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy
 */
class NightMinPriorityStrategy implements RecipientFriendlyStrategyInterface
{
    /**
     * @return int
     */
    public function getPriority(): int
    {
        $hour = Hour::get();

        if ($hour >= 22 || $hour < 8) {
            return Push::PRIORITY_MIN;
        }

        return Push::PRIORITY_MIDDLE;
    }
}
