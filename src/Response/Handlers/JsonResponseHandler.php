<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Core\Http\Response;
use Intersect\Core\Http\ResponseHandler;
use Intersect\Http\Response\JsonResponse;

class JsonResponseHandler implements ResponseHandler {

    public function canHandle(Response $response)
    {
        return ($response instanceof JsonResponse);
    }

    public function handle(Response $response)
    {
        header('Content-Type: application/json');
        echo json_encode($response->getBody());
    }

}