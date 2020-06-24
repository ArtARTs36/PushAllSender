<?php

namespace ArtARTs36\PushAllSender\Validators\Rules;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class TypeRule
 * @package ArtARTs36\PushAllSender\Validators\Rules
 */
class TypeRule implements PushValidateRuleInterface
{
    /**
     * @param Push $push
     * @return bool
     */
    public function isValid(Push $push): bool
    {
        return in_array($push->getType(), Push::TYPES);
    }
}
