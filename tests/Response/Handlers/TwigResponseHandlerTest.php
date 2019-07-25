<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Response\Handlers\TwigResponseHandler;
use Intersect\Http\Response\TwigResponse;
use Intersect\Core\Http\Response;

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