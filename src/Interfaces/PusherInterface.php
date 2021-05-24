<?php

namespace ArtARTs36\PushAllSender\Interfaces;

use ArtARTs36\PushAllSender\Exceptions\PushException;
use ArtARTs36\PushAllSender\Exceptions\PushUndefinedException;
use ArtARTs36\PushAllSender\Exceptions\PushValidateException;
use ArtARTs36\PushAllSender\Exceptions\PushWrongApiKeyException;
use ArtARTs36\PushAllSender\Push;

/**
 * Interface PusherInterface
 * @package ArtARTs36\PushAllSender\Interfaces
 */
interface PusherInterface
{
    public function push(Push $push): bool;

    /**
     * @throws PushValidateException
     * @throws PushUndefinedException
     * @throws PushWrongApiKeyException
     * @throws PushException
     */
    public function pushOrFail(Push $push): bool;
}
