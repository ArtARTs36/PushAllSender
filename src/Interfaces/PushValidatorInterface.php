<?php

namespace ArtARTs36\PushAllSender\Interfaces;

use ArtARTs36\PushAllSender\Push;

/**
 * Interface PushValidatorInterface
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface PushValidatorInterface
{
    public function validate(Push $push): bool;

    public function getLastErrorRule(): ?PushValidateRuleInterface;
}
