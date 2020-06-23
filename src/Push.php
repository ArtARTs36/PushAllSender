<?php

namespace ArtARTs36\PushAllSender;

use ArtARTs36\PushAllSender\Interfaces\PushRecipientInterface;

/**
 * Class Push
 * @package App\Senders
 */
class Push
{
    /** @var string|null  */
    public $title;

    /** @var string|null  */
    public $message;

    /** @var PushRecipientInterface|null  */
    private $recipient;

    /** @var string|null  */
    public $url;

    /**
     * Push constructor.
     * @param string|null $title
     * @param string|null $message
     * @param PushRecipientInterface|null $user
     * @param string|null $url
     */
    public function __construct(
        string $title = null,
        string $message = null,
        PushRecipientInterface $user = null,
        string $url = null) {
        $this->title = $title;
        $this->message = $message;
        $this->recipient = $user;
        $this->url = $url;
    }

    public function getRecipientId()
    {
        return !empty($this->recipient) ?
            $this->recipient->getPushAllId()
            : null;
    }
}
