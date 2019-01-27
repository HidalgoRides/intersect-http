<?php

namespace Intersect\Http\Response\Handlers;

use Intersect\Core\Http\Response;
use Intersect\Core\Http\ResponseHandler;
use Intersect\Http\Response\ViewResponse;

class ViewResponseHandler implements ResponseHandler {

    private $templatePath;

    public function __construct($templatePath)
    {
        $this->templatePath = rtrim($templatePath, '/');
    }

    public function canHandle(Response $response)
    {
        return ($response instanceof ViewResponse);
    }

    /**
     * @param ViewResponse $response
     */
    public function handle(Response $response)
    {
        extract($response->getData());
        require_once $this->templatePath . '/' . ltrim($response->getViewFile(), '/');
    }

}