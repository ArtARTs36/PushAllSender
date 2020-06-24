<?php

namespace ArtARTs36\PushAllSender\Validators\Rules;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class PriorityRule
 * @package ArtARTs36\PushAllSender\Validators\Rules
 */
class PriorityRule implements PushValidateRuleInterface
{
    /**
     * @inheritDoc
     */
    public function isValid(Push $push): bool
    {
        return in_array($push->getPriority(), Push::PRIORITIES);
    }
}
