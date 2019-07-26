<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Response\Response;
use Intersect\Http\Response\Handlers\DefaultResponseHandler;

class DefaultResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $defaultResponseHandler = new DefaultResponseHandler();
        $this->assertTrue($defaultResponseHandler->canHandle(new Response('test')));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_handle_array()
    {
        $defaultResponseHandler = new DefaultResponseHandler();

        ob_start();
        
        $defaultResponseHandler->handle(new Response([
            'unit' => 'test'
        ]));
        
        $response = ob_get_clean();

        $this->assertEquals('{"unit":"test"}', $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_handle_string()
    {
        $defaultResponseHandler = new DefaultResponseHandler();

        ob_start();
        
        $defaultResponseHandler->handle(new Response('unit'));
        
        $response = ob_get_clean();

        $this->assertEquals('unit', $response);
    }

}