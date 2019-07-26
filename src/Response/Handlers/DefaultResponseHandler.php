<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Http\Response\Response;
use Intersect\Http\Response\Handlers\ResponseHandler;

class DefaultResponseHandler implements ResponseHandler {

    public function canHandle(Response $response)
    {
        return true;
    }

    public function handle(Response $response)
    {
        $body = $response->getBody();

        if (is_array($body))
        {
            header('Content-Type: application/json');
            $body = json_encode($body);
        }
        
        echo $body;
    }

}