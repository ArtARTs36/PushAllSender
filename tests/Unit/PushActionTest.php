<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\PushAction;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class PushActionTest
 * @package ArtARTs36\PushAllSender\Tests\Unit
 */
class PushActionTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\PushAction::toArray
     */
    public function testToArray(): void
    {
        $params = ['Click to me', 'https://site.ru'];

        $instance = new PushAction(...$params);

        $response = $instance->toArray();

        //

        self::assertArrayHasKey(PushAllRequest::FIELD_TITLE, $response);
        self::assertEquals($response[PushAllRequest::FIELD_TITLE], $params[0]);

        self::assertArrayHasKey(PushAllRequest::FIELD_URL, $response);
        self::assertEquals($response[PushAllRequest::FIELD_URL], $params[1]);
    }
}
