<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Core\Http\Response;
use Intersect\Http\Response\Handlers\JsonResponseHandler;
use Intersect\Http\Response\JsonResponse;

class JsonResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $jsonResponseHandler = new JsonResponseHandler();
        $this->assertTrue($jsonResponseHandler->canHandle(new JsonResponse(['unit' => 'test'])));   
    }

    public function test_canHandle_invalid()
    {
        $jsonResponseHandler = new JsonResponseHandler();
        $this->assertFalse($jsonResponseHandler->canHandle(new Response('test')));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_handle()
    {
        $jsonResponseHandler = new JsonResponseHandler();

        ob_start();
        
        $jsonResponseHandler->handle(new JsonResponse([
            'unit' => 'test'
        ]));   
        
        $response = ob_get_clean();

        $this->assertEquals('{"unit":"test"}', $response);
    }

}