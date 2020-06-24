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

    /** @var string|null */
    private $bigImage;

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
        return empty($this->actions) && empty($this->bigImage);
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @param string $bigImage
     * @return $this
     */
    public function setBigImage(string $bigImage): self
    {
        $this->bigImage = $bigImage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBigImage(): ?string
    {
        return $this->bigImage;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $array = [];

        if (!empty($this->bigImage)) {
            $array[PushAllRequest::FIELD_BIG_IMAGE] = $this->bigImage;
        }

        if (!empty($this->actions)) {
            $array[PushAllRequest::FIELD_ACTIONS] = array_map(function (PushAction $action) {
                return $action->toArray();
            }, $this->actions);
        }

        return $array;
    }
}
