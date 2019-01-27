<?php

namespace Intersect\Http;

use Intersect\Core\Http\Response;

class DefaultExceptionHandler {

    public function handle(\Exception $e)
    {
        return new Response($this->getErrorMessage($e), 500);
    }

    private function getErrorMessage(\Exception $e)
    {
        return '<h2>Something went wrong!!!</h2>
            <p><strong>Message: </strong>' . $e->getMessage() . '</p>
            <p><strong>File: </strong>' . $e->getFile() . '</p>
            <p><strong>Line Number: </strong>' . $e->getLine() . '</p>';
    }

}