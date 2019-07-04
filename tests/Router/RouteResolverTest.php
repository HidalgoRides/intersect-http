<?php

namespace Tests\Http\Router;

use PHPUnit\Framework\TestCase;
use Intersect\Http\Router\Route;
use Intersect\Http\Router\RouteGroup;
use Intersect\Http\Router\RouteAction;
use Intersect\Http\Router\RouteRegistry;
use Intersect\Http\Router\RouteResolver;

class RouteResolverTest extends TestCase {

    /** @var RouteResolver $routeResolver */
    private $routeResolver;

    /** @var RouteRegistry */
    private $routeRegistry;

    protected function setUp()
    {
        $this->routeRegistry = new RouteRegistry();
        $this->routeRegistry->registerRoute(Route::get('/classpath', 'Tests\Controllers\TestController#index'));
        $this->routeRegistry->registerRoute(Route::get('/classpath-with-params/:id', 'Tests\Controllers\TestController#index2'));
        $this->routeRegistry->registerRoute(Route::get('/closure', (function() {})));
        $this->routeRegistry->registerRoute(Route::get('/closure-with-params/:id', (function() {})));

        $this->routeRegistry->registerRouteGroup(RouteGroup::for('test', [
            Route::get('/group', 'Tests\Controllers\TestController#group')
        ]));

        $this->routeRegistry->registerRouteGroup(RouteGroup::for('prefix', [
            Route::get('/test', 'Tests\Controllers\TestController#prefix'),
            Route::get('/test2', 'Tests\Controllers\TestController#prefix2')
        ], ['prefix' => 'prefix']));

        $this->routeRegistry->registerRoute(Route::get('/extra', 'Tests\Controllers\TestController#extra', [
            'before' => 'test'
        ]));

        $this->routeResolver = new RouteResolver($this->routeRegistry);
    }

    public function test_resolve_routeNotFound() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/not-found');

        $this->assertNull($routeAction);
    }

    public function test_resolve_routeAsIndexPage() 
    {
        $this->routeRegistry->registerRoute(Route::get('/', 'Tests\Controllers\TestController#index'));

        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('index', $routeAction->getMethod());
        $this->assertCount(0, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeAsIndexPageWithVariablePassed() 
    {
        $this->routeRegistry->registerRoute(Route::get('/:id', 'Tests\Controllers\TestController#index'));

        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/123');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('index', $routeAction->getMethod());
        $this->assertCount(1, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeAsIndexPageWithoutVariablePassed() 
    {
        $this->routeRegistry->registerRoute(Route::get('/:id', 'Tests\Controllers\TestController#index'));

        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('index', $routeAction->getMethod());
        $this->assertCount(1, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeAsClassPath() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/classpath');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('index', $routeAction->getMethod());
        $this->assertCount(0, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeAsClassPathWithParameters() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/classpath-with-params/123');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('index2', $routeAction->getMethod());
        $this->assertCount(1, $routeAction->getNamedParameters());
        $this->assertTrue(array_key_exists('id', $routeAction->getNamedParameters()));
        $this->assertEquals(123, $routeAction->getNamedParameters()['id']);
    }

    public function test_resolve_routeAsClosure() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/closure');

        $this->assertNotNull($routeAction);
        $this->assertTrue($routeAction->getIsCallable());
        $this->assertNull($routeAction->getController());
        $this->assertNotNull($routeAction->getMethod());
        $this->assertTrue($routeAction->getMethod() instanceof \Closure);
    }

    public function test_resolve_routeAsClosureWithParameters() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/closure-with-params/123');

        $this->assertNotNull($routeAction);
        $this->assertTrue($routeAction->getIsCallable());
        $this->assertNull($routeAction->getController());
        $this->assertNotNull($routeAction->getMethod());
        $this->assertTrue($routeAction->getMethod() instanceof \Closure);
        $this->assertCount(1, $routeAction->getNamedParameters());
        $this->assertTrue(array_key_exists('id', $routeAction->getNamedParameters()));
        $this->assertEquals(123, $routeAction->getNamedParameters()['id']);
    }

    public function test_resolve_routeInsideOfRouteGroup() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/group');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('group', $routeAction->getMethod());
        $this->assertCount(0, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeRouteGroupWithPrefix() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/prefix/test');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('prefix', $routeAction->getMethod());
        $this->assertCount(0, $routeAction->getNamedParameters());
    }

    public function test_resolve_routeWithExtraOptions() 
    {
        /** @var RouteAction $routeAction */
        $routeAction = $this->routeResolver->resolve('GET', '/extra');

        $this->assertNotNull($routeAction);
        $this->assertFalse($routeAction->getIsCallable());
        $this->assertEquals('Tests\Controllers\TestController', $routeAction->getController());
        $this->assertEquals('extra', $routeAction->getMethod());
        $this->assertCount(0, $routeAction->getNamedParameters());
        $this->assertCount(1, $routeAction->getExtraOptions());
        $this->assertArrayHasKey('before', $routeAction->getExtraOptions());
    }

}