<?php

namespace ArtARTs36\PushAllSender\Exceptions;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;

/**
 * Class PushValidateException
 * @package ArtARTs36\PushAllSender\Exceptions
 */
class PushValidateException extends PushException
{
    /**
     * PushValidateException constructor.
     * @param PushValidateRuleInterface $rule
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(PushValidateRuleInterface $rule, $code = 0, \Throwable $previous = null)
    {
        $message = 'Validation failed in rule: '. get_class($rule);

        parent::__construct($message, $code, $previous);
    }
}
