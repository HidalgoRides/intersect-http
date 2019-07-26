<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Response\Response;
use Intersect\Http\Response\ViewResponse;
use Intersect\Http\Response\Handlers\ViewResponseHandler;

class ViewResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $viewResponseHandler = new ViewResponseHandler('./');
        $this->assertTrue($viewResponseHandler->canHandle(new ViewResponse('./')));   
    }

    public function test_canHandle_invalid()
    {
        $viewResponseHandler = new ViewResponseHandler('./');
        $this->assertFalse($viewResponseHandler->canHandle(new Response('test')));
    }

}