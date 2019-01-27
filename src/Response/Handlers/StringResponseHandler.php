<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Core\Http\Response;
use Intersect\Core\Http\ResponseHandler;

class StringResponseHandler implements ResponseHandler {

    public function canHandle(Response $response)
    {
        return is_string($response->getBody());
    }

    public function handle(Response $response)
    {
        echo $response->getBody();
    }

}