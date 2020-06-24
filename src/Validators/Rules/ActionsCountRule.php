<?php

namespace ArtARTs36\PushAllSender\Validators\Rules;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class ActionsCountRule
 * @package ArtARTs36\PushAllSender\Validators\Rules
 */
class ActionsCountRule implements PushValidateRuleInterface
{
    public const MAX_ACTIONS_COUNT = 2;

    /**
     * @inheritDoc
     */
    public function isValid(Push $push): bool
    {
        return count($push->additional()->getActions()) <= static::MAX_ACTIONS_COUNT;
    }
}
