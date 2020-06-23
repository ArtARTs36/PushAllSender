<?php

namespace ArtARTs36\PushAllSender;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Exceptions\PushIncorrectPriorityException;
use ArtARTs36\PushAllSender\Interfaces\PushRecipientInterface;
use ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy\NightMinPriorityStrategy;
use ArtARTs36\PushAllSender\Strategies\RecipientFriendlyStrategy\RecipientFriendlyStrategyInterface;

/**
 * Class Push
 * @package App\Senders
 */
class Push
{
    public const PRIORITY_MIN = -1;
    public const PRIORITY_MIDDLE = 0;
    public const PRIORITY_MAX = 1;

    public const PRIORITIES = [
        self::PRIORITY_MIN,
        self::PRIORITY_MIDDLE,
        self::PRIORITY_MAX,
    ];

    /** @var string|null  */
    public $title;

    /** @var string|null  */
    public $message;

    /** @var PushRecipientInterface|null  */
    private $recipient;

    /** @var int */
    private $priority;

    /** @var string|null  */
    public $url;

    /**
     * Push constructor.
     * @param string $title
     * @param string $message
     * @param PushRecipientInterface|null $recipient
     * @param string|null $url
     * @param RecipientFriendlyStrategyInterface $recipientFriendlyStrategy
     * @throws PushException
     */
    public function __construct(
        string $title,
        string $message,
        PushRecipientInterface $recipient = null,
        string $url = null,
        RecipientFriendlyStrategyInterface $recipientFriendlyStrategy = null
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->recipient = $recipient;
        $this->url = $url;

        $this->setRecipientFriendlyPriority(
            $recipientFriendlyStrategy ?? new NightMinPriorityStrategy()
        );
    }

    /**
     * @return int|null
     */
    public function getRecipientId(): ?int
    {
        return !empty($this->recipient) ?
            $this->recipient->getPushAllId()
            : null;
    }

    /**
     * @return $this
     */
    public function setMinPriority(): self
    {
        return $this->setPriority(static::PRIORITY_MIN);
    }

    /**
     * @return $this
     */
    public function setMiddlePriority(): self
    {
        return $this->setPriority(static::PRIORITY_MIDDLE);
    }

    /**
     * @return $this
     */
    public function setMaxPriority(): self
    {
        return $this->setPriority(static::PRIORITY_MAX);
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param RecipientFriendlyStrategyInterface $recipientFriendlyStrategy
     * @return $this
     * @throws PushException
     */
    public function setRecipientFriendlyPriority(RecipientFriendlyStrategyInterface $recipientFriendlyStrategy): self
    {
        if (($priority = $recipientFriendlyStrategy->getPriority()) && !$this->checkPriority($priority)) {
            throw new PushIncorrectPriorityException();
        }

        $this->priority = $priority;

        return $this;
    }

    /**
     * @param int $priority
     * @return $this
     */
    protected function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param int $priority
     * @return bool
     */
    protected function checkPriority(int $priority): bool
    {
        return in_array($priority, static::PRIORITIES);
    }
}
