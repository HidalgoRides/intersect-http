<?php

namespace Intersect\Http\Router;

class RouteRegistry {

    protected $registeredRoutes = [];

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->registeredRoutes;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($method, $path = null)
    {
        $method = strtoupper($method);

        $routes = (array_key_exists($method, $this->registeredRoutes) ? $this->registeredRoutes[$method] : []);

        if (is_null($routes) || count($routes) == 0 || is_null($path))
        {
            return $routes;
        }

        return (array_key_exists($path, $routes) ? $routes[$path] : null);
    }

    /**
     * @param Route $route
     */
    public function registerRoute(Route $route)
    {
        $method = strtoupper($route->getMethod());
        $this->registeredRoutes[$method][$route->getPath()] = $route;
    }

    public function registerRouteGroup(RouteGroup $routeGroup)
    {
        $routeGroupConfig = $routeGroup->getRouteConfig();
        $extraOptions = $routeGroup->getExtraOptions();
        
        foreach ($routeGroupConfig as $method => $route)
        {
            if ($route instanceof Route)
            {
                if (array_key_exists('prefix', $extraOptions))
                {
                    $path = '/' . trim($extraOptions['prefix'], '/') . rtrim($route->getPath(), '/');
                    $route->setPath($path);
                }

                if (count($extraOptions) > 0)
                {
                    $routeAction = $route->getRouteAction();
                    $routeAction->setExtraOptions(array_merge_recursive($extraOptions, $routeAction->getExtraOptions()));
                }

                $this->registerRoute($route);
            }
        }
    }

    /**
     * @param $key
     */
    public function unregister($key)
    {
        if (array_key_exists($key, $this->registeredRoutes))
        {
            unset($this->registeredRoutes[$key]);
        }
    }

}