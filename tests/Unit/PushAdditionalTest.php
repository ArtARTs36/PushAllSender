<?php

namespace ArtARTs36\PushAllSender\Tests\Unit;

use ArtARTs36\PushAllSender\PushAction;
use ArtARTs36\PushAllSender\PushAdditional;
use ArtARTs36\PushAllSender\Requests\PushAllRequest;
use PHPUnit\Framework\TestCase;

class PushAdditionalTest extends TestCase
{
    /**
     * @covers \ArtARTs36\PushAllSender\PushAdditional::isEmpty
     */
    public function testCreateInstance(): void
    {
        $instance = new PushAdditional();

        self::assertTrue($instance->isEmpty());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\PushAdditional::toArray
     */
    public function testToArray(): void
    {
        $instance = new PushAdditional();

        self::assertEmpty($instance->toArray());

        //

        $title = 'Title';
        $url = 'http://site.ru';

        $instance->addAction($title, $url);

        $response = $instance->toArray();

        self::assertArrayHasKey(
            PushAllRequest::FIELD_ACTIONS,
            $adds = $response
        );

        self::assertArrayHasKey(0, $actions = $adds[PushAllRequest::FIELD_ACTIONS]);
        self::assertArrayHasKey(PushAllRequest::FIELD_TITLE, $actions[0]);
        self::assertArrayHasKey(PushAllRequest::FIELD_URL, $actions[0]);
        self::assertEquals($title, $actions[0][PushAllRequest::FIELD_TITLE]);
        self::assertEquals($url, $actions[0][PushAllRequest::FIELD_URL]);
    }

    /**
     * @covers \ArtARTs36\PushAllSender\PushAdditional::clearActions
     * @covers \ArtARTs36\PushAllSender\PushAdditional::isEmpty
     */
    public function testClearActions(): void
    {
        $instance = new PushAdditional();

        $instance->addAction('Title', 'http://site.ru');

        self::assertFalse($instance->isEmpty());

        $instance->clearActions();

        self::assertTrue($instance->isEmpty());
    }

    /**
     * @covers \ArtARTs36\PushAllSender\PushAdditional::getActions
     * @covers \ArtARTs36\PushAllSender\PushAdditional::addAction
     */
    public function testGetActions(): void
    {
        $instance = new PushAdditional();

        $title = 'Title';
        $url = 'http://site.ru';

        $action = new PushAction($title, $url);

        self::assertCount(0, $instance->getActions());

        $instance->addAction($title, $url);

        $response = $instance->getActions();

        self::assertArrayHasKey(0, $response);
        self::assertEquals($action->toArray(), $response[0]->toArray());
    }
}
