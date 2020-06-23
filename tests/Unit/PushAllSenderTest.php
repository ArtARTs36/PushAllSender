<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Exceptions\PushUndefinedException;
use ArtARTs36\PushAllSender\Exceptions\PushWrongApiKeyException;
use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Senders\PushAllSender;
use PHPUnit\Framework\TestCase;

/**
 * Class PushAllSenderTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushAllSenderTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Senders\PushAllSender::pushOrFail
     * @throws \ArtARTs36\PushAllSender\Exceptions\PushException
     */
    public function testBadWrongApiKey(): void
    {
        $push = new Push('Title', 'Message');

        $sender = new class(123456789, 't5llLrw3rLr3wrwDefer') extends PushAllSender {
            protected function send(array $data): bool
            {
                $this->parseAnswer(json_encode(['error' => PushAllSender::ERROR_WRONG_KEY]));

                return $this->analyseAnswer();
            }
        };

        self::expectException(PushWrongApiKeyException::class);

        $sender->pushOrFail($push);
    }

    /**
     * @covers \ArtARTs36\PushAllSender\Senders\PushAllSender::pushOrFail
     * @throws \ArtARTs36\PushAllSender\Exceptions\PushException
     */
    public function testBadUndefined(): void
    {
        $push = new Push('Title', 'Message');

        $sender = new class(123456789, 't5llLrw3rLr3wrwDefer') extends PushAllSender {
            protected function send(array $data): bool
            {
                $this->parseAnswer('{}');

                return $this->analyseAnswer();
            }
        };

        self::expectException(PushUndefinedException::class);

        $sender->pushOrFail($push);
    }
}
