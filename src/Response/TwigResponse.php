<?php

namespace Intersect\Http\Response;

use Intersect\Http\Response\Response;

class TwigResponse extends Response {

    private $data = [];
    private $templateFile;

    public function __construct($templateFile, array $data = [], int $status = 200)
    {
        parent::__construct(null, $status);

        $this->data = $data;
        $this->templateFile = $templateFile;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getTemplateFile()
    {
        return $this->templateFile;
    }

}