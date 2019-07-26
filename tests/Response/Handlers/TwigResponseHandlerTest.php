<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Response\TwigResponse;
use Intersect\Http\Response\Response;
use Intersect\Http\Response\Handlers\TwigResponseHandler;

class TwigResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $twigResponseHandler = new TwigResponseHandler('./');
        $this->assertTrue($twigResponseHandler->canHandle(new TwigResponse('./')));
    }

    public function test_canHandle_invalid()
    {
        $twigResponseHandler = new TwigResponseHandler('./');
        $this->assertFalse($twigResponseHandler->canHandle(new Response('test')));
    }

}