<?php

namespace ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy;

/**
 * Interface RecipientFriendlyStrategyInterface
 * @package ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy
 */
interface RecipientFriendlyStrategyInterface
{
    /**
     * @return int
     */
    public function getPriority(): int;
}
