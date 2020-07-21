<?php

declare(strict_types=1);

namespace Permafrost\CurrentRoute;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

/**
 * Class CurrentRouteInfo.
 *
 * @property string|null action
 * @property string|null name
 * @property \Illuminate\Routing\Route|null route
 * @property string|null uri
 */
final class CurrentRouteInfo
{
    /** @var Illuminate\Routing\Route|null $route */
    protected $route;

    /** @var Illuminate\Routing\Router $router */
    protected $router;

    /**
     * Returns a new instance of the CurrentRouteInfo class.
     *
     * @return \Permafrost\CurrentRoute\CurrentRouteInfo
     */
    public static function create($router = null): CurrentRouteInfo
    {
        return new self($router);
    }

    public function __construct($router = null)
    {
        $this->router = $router ? $router : \app()->make(Router::class);
        $this->route = $this->router->getCurrentRoute();
    }

    /**
     * Dynamically access route properties.
     *
     * @param $name
     *
     * @return \Illuminate\Routing\Route|null
     */
    public function __get($name)
    {
        $validNames = [
            'action',
            'name',
            'route',
            'uri',
        ];

        if (in_array($name, $validNames, true)) {
            return $this->$name();
        }
    }

    /**
     * Returns the current route's action name.
     *
     * @return string|null
     */
    public function action(): ?string
    {
        return optional($this->route)->getActionName();
    }

    /**
     * Returns the current route's name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return optional($this->route)->getName();
    }

    /**
     * Returns the current route.
     *
     * @return \Illuminate\Routing\Route|null
     */
    public function route(): ?Route
    {
        return $this->route;
    }

    /**
     * Returns the current route's uri pattern.
     *
     * @return string|null
     */
    public function uri(): ?string
    {
        return optional($this->route)->uri();
    }

    /**
     * Checks the current route name against either a string pattern or
     * an array of patterns (wildcards permitted).
     *
     * @param array|string $patterns
     *
     * @return bool
     */
    public function nameMatches($patterns): bool
    {
        if (is_string($patterns)) {
            $patterns = [$patterns];
        }

        $routeName = $this->name();

        if ($routeName === null) {
            return false;
        }

        foreach ($patterns as $pattern) {
            $pattern = preg_quote($pattern, '#');
            $pattern = str_replace('\*', '.*', $pattern);

            if (preg_match('#^'.$pattern.'\z#u', $routeName) === 1) {
                return true;
            }
        }

        return false;
        //return $this->router->currentRouteNamed($patterns);
    }

    /**
     * Returns true if $name matches the current route name, otherwise false.
     *
     * @param string $name
     *
     * @return bool
     */
    public function named(string $name): bool
    {
        return $this->name === $name;
    }

    /**
     * Returns true if the current route uses the specified middleware,
     * otherwise false.
     *
     * @param string $name
     *
     * @return bool
     */
    public function usesMiddleware(string $name): bool
    {
        return in_array($name, $this->middleware(), true);
    }

    /**
     * Returns all of the middleware used by the current route.
     *
     * @return array
     */
    public function middleware(): array
    {
        return $this->route->controllerMiddleware();
    }
}
