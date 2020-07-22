<?php

namespace Permafrost\CurrentRoute\Tests;

//use PHPUnit\Framework\TestCase;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase;
use Permafrost\CurrentRoute\CurrentRouteInfo;

class CurrentRouteInfoTest extends TestCase
{
    protected function getRouterStub()
    {
        $routeStub = $this->getMockBuilder(Route::class)
            ->setMockClassName('Route')
            ->disableOriginalConstructor()
            ->enableArgumentCloning()
            ->enableAutoload()
            ->getMock();

        $routeStub->method('getActionName')->willReturn('Controller@index');
        $routeStub->method('getName')->willReturn('web.test.index');
        $routeStub->method('uri')->willReturn('/test');
        $routeStub->method('controllerMiddleware')->willReturn(['web']);

        $routerStub = $this->createStub(Router::class);

        $routerStub->method('getCurrentRoute')->willReturn($routeStub);

        return $routerStub;
    }

    protected function getCurrentRouteInfoObject(): CurrentRouteInfo
    {
        return new CurrentRouteInfo($this->getRouterStub());
    }

    public function test_current_route_info_uri_method_result_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertSame('/test', $info->uri());
    }

    public function test_current_route_info_name_method_result_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertSame('web.test.index', $info->name());
    }

    public function test_current_route_info_action_method_result_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertSame('Controller@index', $info->action());
    }

    public function test_current_route_info_action_method_name_result_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertSame('index', $info->actionMethod());
    }

    public function test_current_route_info_named_method_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertTrue($info->named('web.test.index'));
        $this->assertFalse($info->named('api.test.index'));
    }

    public function test_current_route_info_name_matches_method_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertTrue($info->nameMatches(['web.test.*']));
        $this->assertTrue($info->nameMatches('web.test.*'));
        $this->assertFalse($info->nameMatches(['api.test.*', 'web.fail.*']));
    }

    public function test_current_route_info_create_method_returns_correct_class(): void
    {
        $info = CurrentRouteInfo::create($this->getRouterStub());

        $this->assertSame(CurrentRouteInfo::class, get_class($info));
    }

    public function test_current_route_info_middlware_method_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertIsArray($info->middleware());
        $this->assertEquals(['web'], $info->middleware());
    }

    public function test_current_route_info_uses_middlware_method_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $this->assertTrue($info->usesMiddleware('web'));
        $this->assertFalse($info->usesMiddleware('api'));
    }

    public function test_current_route_info_route_method_is_correct(): void
    {
        $info = $this->getCurrentRouteInfoObject();

        $partsExpected = explode('\\', Route::class);
        $baseclassExpected = array_pop($partsExpected);

        $parts = explode('\\', get_class($info->route()));
        $baseclass = array_pop($parts);

        $this->assertSame($baseclassExpected, $baseclass);
    }
}
