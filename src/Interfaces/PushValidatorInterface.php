<?php

namespace ArtARTs36\PushAllSender\Interfaces;

use ArtARTs36\PushAllSender\Push;

/**
 * Interface PushValidatorInterface
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface PushValidatorInterface
{
    /**
     * @param Push $push
     * @return bool
     */
    public function validate(Push $push): bool;

    /**
     * @return PushValidateRuleInterface
     */
    public function getLastErrorRule(): ?PushValidateRuleInterface;
}
