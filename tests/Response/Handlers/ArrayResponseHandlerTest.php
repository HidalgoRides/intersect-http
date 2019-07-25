<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Core\Http\Response;
use Intersect\Http\Response\Handlers\ArrayResponseHandler;

class ArrayResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $arrayResponseHandler = new ArrayResponseHandler();
        $this->assertTrue($arrayResponseHandler->canHandle(new Response([])));   
    }

    public function test_canHandle_invalid()
    {
        $arrayResponseHandler = new ArrayResponseHandler();
        $this->assertFalse($arrayResponseHandler->canHandle(new Response('test')));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_handle()
    {
        $arrayResponseHandler = new ArrayResponseHandler();

        ob_start();
        
        $arrayResponseHandler->handle(new Response([
            'unit' => 'test'
        ]));   
        
        $response = ob_get_clean();

        $this->assertEquals('{"unit":"test"}', $response);
    }

}