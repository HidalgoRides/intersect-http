<?php

namespace Intersect\Http\Router;

use Intersect\Http\Router\Route;
use Intersect\Http\Router\RouteAction;
use Intersect\Http\Router\RouteRegistry;

class RouteResolver {

    /** @var RouteRegistry */
    private $routeRegistry;

    /**
     * @param array $registeredRoutes
     */
    public function __construct(RouteRegistry $routeRegistry)
    {
        $this->routeRegistry = $routeRegistry;
    }

    /**
     * @return RouteAction
     */
    public function resolve($method, $baseUri)
    {
        $registeredRoutes = $this->routeRegistry->get($method);

        if (is_null($registeredRoutes))
        {
            return null;
        }

        $routeAction = null;

        /** @var Route $route */
        foreach ($registeredRoutes as $path => $route)
        {
            $namedParameters = [];

            $path = rtrim($path, '/');

            if (preg_match_all('#:([a-z0-9]+)/?#i', $path, $placeholders))
            {
                foreach ($placeholders[1] as $placeholder)
                {
                    $namedParameters[$placeholder] = null;
                    $path = str_replace(':' . $placeholder, '+(?P<' . $placeholder . '>[^/$]+)?', $path);
                }
            }

            $url = '/' . trim($baseUri, '/');

            if (!preg_match('#^' . $path . '$#', $url, $matches))
            {
                continue;
            }

            foreach ($matches as $key => $value)
            {
                if (array_key_exists($key, $namedParameters))
                {
                    $namedParameters[$key] = $value;
                }
            }

            $routeAction = $route->getRouteAction();
            $routeAction->setNamedParameters($namedParameters);

            break;
        }

        return $routeAction;
    }

}