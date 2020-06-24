<?php

namespace ArtARTs36\PushAllSender\Requests;

use ArtARTs36\PushAllSender\Push;

/**
 * Class PushAllRequest
 * @package ArtARTs36\PushAllSender\Requests
 */
class PushAllRequest
{
    public const FIELD_TYPE = 'type';
    public const FIELD_API_KEY = 'key';
    public const FIELD_CHANNEL_ID = 'id';
    public const FIELD_MESSAGE = 'message';
    public const FIELD_TITLE = 'title';
    public const FIELD_URL = 'url';
    public const FIELD_PRIORITY = 'priority';
    public const FIELD_UID = 'uid';

    private $attributes;

    public function __construct(
        int $channelId,
        string $apiKey,
        Push $push
    ) {
        $this->attributes = [
            static::FIELD_TYPE => $push->getType(),
            static::FIELD_CHANNEL_ID => $channelId,
            static::FIELD_API_KEY => $apiKey,
            static::FIELD_MESSAGE => $push->message,
            static::FIELD_TITLE => $push->title,
            static::FIELD_PRIORITY => $push->getPriority(),
        ];

        $this->setAttributeWhen(!empty($push->url), static::FIELD_URL, $push->url);

        if (($id = $push->getRecipientId())) {
            $this->setType(Push::TYPE_UNICAST);
            $this->setAttribute(static::FIELD_UID, $push->getRecipientId());
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        return $this->setAttribute(static::FIELD_TYPE, $type);
    }

    /**
     * @param bool $condition
     * @param $field
     * @param $value
     * @return $this
     */
    protected function setAttributeWhen(bool $condition, $field, $value): self
    {
        ($condition === true) && $this->setAttribute($field, $value);

        return $this;
    }

    /**
     * @param mixed $field
     * @param mixed $value
     * @return $this
     */
    protected function setAttribute($field, $value): self
    {
        $this->attributes[$field] = $value;

        return $this;
    }
}
