<?php

namespace ArtARTs36\PushAllSender\Validators\Rules;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class MessageLengthRule
 * @package ArtARTs36\PushAllSender\Validators\Rules
 */
class MessageLengthRule implements PushValidateRuleInterface
{
    /** @var int */
    public const MAX_LENGTH = 500;

    public function isValid(Push $push): bool
    {
        return mb_strlen($push->message) <= static::MAX_LENGTH;
    }
}
