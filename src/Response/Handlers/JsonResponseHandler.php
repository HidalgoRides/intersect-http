<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Http\Response\Response;
use Intersect\Http\Response\JsonResponse;
use Intersect\Http\Response\Handlers\ResponseHandler;

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