<?php

namespace ArtARTs36\PushAllSender;

use ArtARTs36\PushAllSender\Interfaces\Arrayable;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;

/**
 * Class PushAction
 * @package ArtARTs36\PushAllSender
 */
final class PushAction implements Arrayable
{
    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /**
     * PushAction constructor.
     * @param string $title
     * @param string $url
     */
    public function __construct(string $title, string $url)
    {
        $this->title = $title;
        $this->url = $url;
    }

    public function toArray(): array
    {
        return [
            PushAllRequest::FIELD_TITLE => $this->title,
            PushAllRequest::FIELD_URL => $this->url,
        ];
    }
}
