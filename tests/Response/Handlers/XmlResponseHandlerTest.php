<?php

namespace Tests\Http\Response\Handlers;

use PHPUnit\Framework\TestCase;
use Intersect\Core\Http\Response;
use Intersect\Http\Response\Handlers\XmlResponseHandler;
use Intersect\Http\Response\XmlResponse;

class XmlResponseHandlerTest extends TestCase {

    public function test_canHandle_valid()
    {
        $xmlResponseHandler = new XmlResponseHandler();
        $this->assertTrue($xmlResponseHandler->canHandle(new XmlResponse(['unit' => 'test'])));   
    }

    public function test_canHandle_invalid()
    {
        $xmlResponseHandler = new XmlResponseHandler();
        $this->assertFalse($xmlResponseHandler->canHandle(new Response('test')));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_handle()
    {
        $xmlResponseHandler = new XmlResponseHandler();

        ob_start();
        
        $xmlResponseHandler->handle(new XmlResponse([
            'unit' => 'test',
            'foo' => [
                'bar' => 'bell'
            ],
            'taco' => [
                ['shell' => 'soft'],
                ['shell' => 'hard']
            ]
        ]));   
        
        $response = ob_get_clean();

        $response = str_replace("\n", '', $response);
        $response = str_replace("  ", '', $response);

        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?><root><unit>test</unit><foo><bar>bell</bar></foo><taco><shell>soft</shell></taco><taco><shell>hard</shell></taco></root>', $response);
    }

}