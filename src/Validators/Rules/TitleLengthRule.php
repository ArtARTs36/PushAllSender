<?php

namespace ArtARTs36\PushAllSender\Validators\Rules;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class TitleLengthRule
 * @package ArtARTs36\PushAllSender\Validators\Rules
 */
class TitleLengthRule implements PushValidateRuleInterface
{
    /** @var int */
    public const MAX_LENGTH = 100;

    public function isValid(Push $push): bool
    {
        return mb_strlen($push->title) <= static::MAX_LENGTH;
    }
}
