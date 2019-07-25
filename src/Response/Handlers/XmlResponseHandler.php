<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Core\Http\Response;
use Intersect\Core\Http\ResponseHandler;
use Intersect\Http\Response\XmlResponse;
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
        echo ArrayToXmlConverter::convert($response->getBody());
    }

}