<?php

namespace Intersect\Http\Response;

use Intersect\Core\Http\Response;

class XmlResponse extends Response {

    protected $rootElement;

    public function __construct(array $body, $rootElement = 'root', int $status = 200)
    {
        parent::__construct($body, $status);
        $this->rootElement = $rootElement;
    }

    public function getRootElement()
    {
        return $this->rootElement;
    }

}