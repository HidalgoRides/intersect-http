<?php

namespace Intersect\Http\Response;

use Intersect\Http\Response\Response;

class ViewResponse extends Response {

    private $data = [];
    private $viewFile;

    public function __construct($viewFile, array $data = [], $status = 200)
    {
        $this->viewFile = $viewFile;
        $this->data = $data;

        parent::__construct(null, $status);
    }

    public function getViewFile()
    {
        return $this->viewFile;
    }

    public function getData()
    {
        return $this->data;
    }

}