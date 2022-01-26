<?php

namespace ArtARTs36\PushAllSender\Interfaces;

/**
 * Interface Arrayable
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface Arrayable
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
