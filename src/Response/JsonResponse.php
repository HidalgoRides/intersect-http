<?php

namespace Intersect\Http\Response;

use Intersect\Core\Http\Response;

class JsonResponse extends Response {

    public function __construct($body, int $status = 200)
    {
        header('Content-Type: application/json');
        parent::__construct(json_encode($body), $status);
    }

}