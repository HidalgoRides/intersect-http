<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Core\Http\Response;
use Intersect\Core\Http\ResponseHandler;

class ArrayResponseHandler implements ResponseHandler {

    public function canHandle(Response $response)
    {
        return is_array($response->getBody());
    }

    public function handle(Response $response)
    {
        header('Content-Type: application/json');
        echo json_encode($response->getBody());
    }

}