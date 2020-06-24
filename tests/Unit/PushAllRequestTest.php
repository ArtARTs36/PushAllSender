<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\Push;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class PushAllRequestTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushAllRequestTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\Requests\PushAllRequest
     */
    public function test(): void
    {
        $channelId = 55555;
        $apiKey = '55wrewfewfew';
        $title = 'Title';
        $message = 'Message';

        $push = new Push($title, $message);

        $request = new PushAllRequest(
            $channelId,
            $apiKey,
            $push
        );

        $attributes = $request->getAttributes();

        //

        self::assertArrayHasKey(PushAllRequest::FIELD_CHANNEL_ID, $attributes);
        self::assertEquals($channelId, $attributes[PushAllRequest::FIELD_CHANNEL_ID]);

        self::assertArrayHasKey(PushAllRequest::FIELD_API_KEY, $attributes);
        self::assertEquals($apiKey, $attributes[PushAllRequest::FIELD_API_KEY]);

        self::assertArrayHasKey(PushAllRequest::FIELD_TYPE, $attributes);
        self::assertEquals(Push::TYPE_BROADCAST, $attributes[PushAllRequest::FIELD_TYPE]);

        self::assertArrayHasKey(PushAllRequest::FIELD_MESSAGE, $attributes);
        self::assertEquals($message, $attributes[PushAllRequest::FIELD_MESSAGE]);

        self::assertArrayHasKey(PushAllRequest::FIELD_TITLE, $attributes);
        self::assertEquals($title, $attributes[PushAllRequest::FIELD_TITLE]);

        self::assertArrayHasKey(PushAllRequest::FIELD_PRIORITY, $attributes);
        self::assertEquals($push->getPriority(), $attributes[PushAllRequest::FIELD_PRIORITY]);

        self::assertArrayNotHasKey(PushAllRequest::FIELD_UID, $attributes);
    }
}
