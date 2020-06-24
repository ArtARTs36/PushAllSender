<?php

namespace ArtARTs36\PushAllSender\Interfaces;

use ArtARTs36\PushAllSender\Push;

/**
 * Interface PushValidateRuleInterface
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface PushValidateRuleInterface
{
    /**
     * @param Push $push
     * @return bool
     */
    public function isValid(Push $push): bool;
}
