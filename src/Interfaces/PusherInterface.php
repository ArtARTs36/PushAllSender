<?php

namespace ArtARTs36\PushAllSender\Interfaces;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Push;

/**
 * Interface PusherInterface
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface PusherInterface
{
    /**
     * @param Push $push
     * @return mixed
     */
    public function push(Push $push): bool;

    /**
     * @param Push $push
     * @throws PushException
     * @return mixed
     */
    public function pushOrFail(Push $push): bool;
}
