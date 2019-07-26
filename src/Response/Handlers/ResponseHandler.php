<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Http\Response\Response;

interface ResponseHandler {

    public function canHandle(Response $response);

    public function handle(Response $response);

}