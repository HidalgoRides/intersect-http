<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Http\Response\Response;
use Intersect\Http\Response\XmlResponse;
use Intersect\Http\Response\Handlers\ResponseHandler;
use TimKippDev\ArrayToXmlConverter\ArrayToXmlConverter;

class XmlResponseHandler implements ResponseHandler {

    protected $document;

    public function canHandle(Response $response)
    {
        return ($response instanceof XmlResponse);
    }

    /**
     * @param XmlResponse $response
     */
    public function handle(Response $response)
    {
        header('Content-Type: application/xml');
        echo ArrayToXmlConverter::convert($response->getBody(), $response->getOptions());
    }

}