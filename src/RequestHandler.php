<?php

namespace Intersect\Http;

use Intersect\Core\Http\Request;
use Intersect\Core\Http\Response;
use Intersect\Core\MethodInvoker;
use Intersect\Core\ClosureInvoker;
use Intersect\Http\ExceptionHandler;
use Intersect\Http\Router\RouteRegistry;
use Intersect\Http\Router\RouteResolver;

class RequestHandler {

    /** @var ClosureInvoker $closureInvoker */
    private $closureInvoker;

    /** @var ExceptionHandler */
    private $exceptionHandler;

    /** @var MethodInvoker $methodInvoker */
    private $methodInvoker;

    /** @var RouteResolver */
    private $routeResolver;

    private $preInvocationCallback;

    /**
     * RequestHandler constructor.
     */
    public function __construct(RouteRegistry $routeRegistry, ClosureInvoker $closureInvoker, MethodInvoker $methodInvoker, ExceptionHandler $customExceptionHandler = null)
    {
        $this->closureInvoker = $closureInvoker;
        $this->methodInvoker = $methodInvoker;

        $this->routeResolver = new RouteResolver($routeRegistry);

        $this->exceptionHandler = (!is_null($customExceptionHandler)) ? $customExceptionHandler : new DefaultExceptionHandler();
    }

    public function setPreInvocationCallback(\Closure $callback)
    {
        $this->preInvocationCallback = $callback;
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function handle(Request $request)
    {
        $response = null;

        try {
            $routeAction = $this->routeResolver->resolve($request->getMethod(), $request->getBaseUri());

            if (is_null($routeAction))
            {
                throw new \Exception('Route not found: [uri: ' . $request->getBaseUri() . ']');
            }

            if ($routeAction->getIsCallable())
            {
                $response = $this->closureInvoker->invoke($routeAction->getMethod(), $routeAction->getNamedParameters());
            }
            else
            {
                $controllerClass = $routeAction->getController();

                if (is_null($controllerClass))
                {
                    throw new \Exception('Controller not found: [name: ' . $routeAction->getController() . ']');
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $routeAction->getMethod()))
                {
                    throw new \Exception('Method not found: [name: ' . $routeAction->getController() . '#' . $routeAction->getMethod() . ']');
                }

                if (!is_null($this->preInvocationCallback))
                {
                    $callback = $this->preInvocationCallback;
                    $callback($controller);
                }

                $response = $this->methodInvoker->invoke($controller, $routeAction->getMethod(), $routeAction->getNamedParameters());
            }
        } catch (\Exception $e) {
            $response = $this->exceptionHandler->handle($e);
        }

        if (!$response instanceof Response)
        {
            $response = new Response($response);
        }

        return $response;
    }



}