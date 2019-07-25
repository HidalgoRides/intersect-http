<?php

namespace Intersect\Http\Response;

use Intersect\Core\Http\Response;

class XmlResponse extends Response {

    protected $options;

    public function __construct(array $body, array $options = [], int $status = 200)
    {
        parent::__construct($body, $status);
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

}