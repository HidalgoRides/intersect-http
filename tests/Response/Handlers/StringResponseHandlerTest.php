<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Response\Handlers\StringResponseHandler;
use Intersect\Core\Http\Response;

class StringResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $stringResponseHandler = new StringResponseHandler();
        $this->assertTrue($stringResponseHandler->canHandle(new Response('test')));
    }

    public function test_canHandle_invalid()
    {
        $stringResponseHandler = new StringResponseHandler();
        $this->assertFalse($stringResponseHandler->canHandle(new Response([])));
    }

}