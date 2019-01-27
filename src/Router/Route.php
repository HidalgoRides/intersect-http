<?php

namespace Intersect\Http\Router;

use Intersect\Http\Router\RouteAction;

class Route {

    private $action;
    private $method;
    private $path;
    /** @var RouteAction */
    private $routeAction;
    
    public static function get($path, $action, $extraOptions = [])
    {
        return self::newRouteForMethod('GET', $path, $action, $extraOptions);
    }

    public static function post($path, $action, $extraOptions = [])
    {
        return self::newRouteForMethod('POST', $path, $action, $extraOptions);
    }

    public static function delete($path, $action, $extraOptions = [])
    {
        return self::newRouteForMethod('DELETE', $path, $action, $extraOptions);
    }

    public static function put($path, $action, $extraOptions = [])
    {
        return self::newRouteForMethod('PUT', $path, $action, $extraOptions);
    }

    private static function newRouteForMethod($method, $path, $action, $extraOptions = [])
    {
        $route = new Route();
        $route->setMethod(strtoupper($method));
        $route->setPath($path);
        $route->setAction($action);

        $routeAction = $route->getRouteAction();
        $routeAction->setExtraOptions($extraOptions);

        if ($action instanceof \Closure)
        {
            $routeAction->setMethod($action);
            $routeAction->setIsCallable(true);
        }
        else
        {
            $methodParts = explode('#', $action);

            if (isset($methodParts[1]))
            {
                $routeAction->setController($methodParts[0]);
                $routeAction->setMethod($methodParts[1]);
            }
        }

        return $route;
    }

    private function __construct()
    {
        $this->routeAction = new RouteAction();
    }

    public function getRouteAction()
    {
        return $this->routeAction;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

}