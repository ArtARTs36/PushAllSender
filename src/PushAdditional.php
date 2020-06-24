<?php

namespace ArtARTs36\PushAllSender;

use ArtARTs36\PushAllSender\Interfaces\Arrayable;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;

/**
 * Class PushAdditional
 * @package ArtARTs36\PushAllSender
 */
class PushAdditional implements Arrayable
{
    /** @var array|PushAction[] */
    private $actions = [];

    /**
     * @param string $title
     * @param string $url
     * @return $this
     */
    public function addAction(string $title, string $url): self
    {
        $this->actions[] = new PushAction($title, $url);

        return $this;
    }

    /**
     * @return $this
     */
    public function clearActions(): self
    {
        $this->actions = [];

        return $this;
    }

    /**
     * @return array|PushAction[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->actions);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            PushAllRequest::FIELD_ACTIONS => array_map(function (PushAction $action) {
                return $action->toArray();
            }, $this->actions),
        ];
    }
}
