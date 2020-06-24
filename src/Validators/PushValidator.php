<?php

namespace ArtARTs36\PushAllSender\Validators;

use ArtARTs36\PushAllSender\Interfaces\PushValidateRuleInterface;
use ArtARTs36\PushAllSender\Interfaces\PushValidatorInterface;
use ArtARTs36\PushAllSender\Push;

/**
 * Class PushValidator
 * @package ArtARTs36\PushAllSender\Validators
 */
class PushValidator implements PushValidatorInterface
{
    /** @var array|PushValidateRuleInterface[] */
    protected $rules;

    /** @var PushValidateRuleInterface|null */
    protected $lastErrorRule;

    /**
     * PushValidator constructor.
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = array_map(function ($class) {
            return new $class;
        }, $rules);
    }

    /**
     * @param Push $push
     * @return bool
     */
    public function validate(Push $push): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->isValid($push)) {
                $this->lastErrorRule = $rule;

                return false;
            }
        }

        return true;
    }

    /**
     * @return PushValidateRuleInterface|null
     */
    public function getLastErrorRule(): ?PushValidateRuleInterface
    {
        return $this->lastErrorRule;
    }
}
