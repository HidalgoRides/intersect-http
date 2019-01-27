<?php

namespace Tests\Http\Router;

use Intersect\Http\Router\Route;
use Intersect\Http\Router\RouteGroup;
use Intersect\Http\Router\RouteRegistry;
use Intersect\Http\Router\RouteResolver;
use PHPUnit\Framework\TestCase;

class RouteResolverTest extends TestCase {

    /** @var RouteResolver $routeResolver */
    private $routeResolver;

    protected function setUp()
    {
        $routeRegistry = new RouteRegistry();
        $routeRegistry->registerRoute(Route::get('/classpath', 'Tests\Controllers\TestController#index'));
        $routeRegistry->registerRoute(Route::get('/classpath-with-params/:id', 'Tests\Controllers\TestController#index2'));
        $routeRegistry->registerRoute(Route::get('/closure', (function() {})));
        $routeRegistry->registerRoute(Route::get('/closure-with-params/:id', (function() {})));

        $routeRegistry->registerRouteGroup(RouteGroup::for('test', [
            Route::get('/group', 'Tests\Controllers\TestController#group')
        ]));

        $routeRegistry->registerRouteGroup(RouteGroup::for('prefix', [
            Route::get('/test', 'Tests\Controllers\TestController#prefix'),
            Route::get('/test2', 'Tests\Controllers\TestController#prefix2')
        ], ['prefix' => 'prefix']));

        $routeRegistry->registerRoute(Route::get('/extra', 'Tests\Controllers\TestController#extra', [
            'before' => 'test'
        ]));

        $this->routeResolver = new RouteResolver($routeRegistry);
    }

    public function test_resolve_routeNotFound() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/not-found');

        $this->assertNull($route);
    }

    public function test_resolve_routeAsClassPath() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/classpath');

        $this->assertNotNull($route);
        $this->assertFalse($route->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $route->getController());
        $this->assertEquals('index', $route->getMethod());
        $this->assertCount(0, $route->getNamedParameters());
    }

    public function test_resolve_routeAsClassPathWithParameters() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/classpath-with-params/123');

        $this->assertNotNull($route);
        $this->assertFalse($route->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $route->getController());
        $this->assertEquals('index2', $route->getMethod());
        $this->assertCount(1, $route->getNamedParameters());
        $this->assertTrue(array_key_exists('id', $route->getNamedParameters()));
        $this->assertEquals(123, $route->getNamedParameters()['id']);
    }

    public function test_resolve_routeAsClosure() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/closure');

        $this->assertNotNull($route);
        $this->assertTrue($route->getIsCallable());
        $this->assertNull($route->getController());
        $this->assertNotNull($route->getMethod());
        $this->assertTrue($route->getMethod() instanceof \Closure);
    }

    public function test_resolve_routeAsClosureWithParameters() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/closure-with-params/123');

        $this->assertNotNull($route);
        $this->assertTrue($route->getIsCallable());
        $this->assertNull($route->getController());
        $this->assertNotNull($route->getMethod());
        $this->assertTrue($route->getMethod() instanceof \Closure);
        $this->assertCount(1, $route->getNamedParameters());
        $this->assertTrue(array_key_exists('id', $route->getNamedParameters()));
        $this->assertEquals(123, $route->getNamedParameters()['id']);
    }

    public function test_resolve_routeInsideOfRouteGroup() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/group');

        $this->assertNotNull($route);
        $this->assertFalse($route->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $route->getController());
        $this->assertEquals('group', $route->getMethod());
        $this->assertCount(0, $route->getNamedParameters());
    }

    public function test_resolve_routeRouteGroupWithPrefix() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/prefix/test');

        $this->assertNotNull($route);
        $this->assertFalse($route->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $route->getController());
        $this->assertEquals('prefix', $route->getMethod());
        $this->assertCount(0, $route->getNamedParameters());
    }

    public function test_resolve_routeWithExtraOptions() 
    {
        /** @var Route $route */
        $route = $this->routeResolver->resolve('GET', '/extra');

        $this->assertNotNull($route);
        $this->assertFalse($route->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $route->getController());
        $this->assertEquals('extra', $route->getMethod());
        $this->assertCount(0, $route->getNamedParameters());
        $this->assertCount(1, $route->getExtraOptions());
        $this->assertArrayHasKey('before', $route->getExtraOptions());
    }

}