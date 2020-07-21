<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use Permafrost\CurrentRoute\CurrentRouteInfo;

/**
 * Returns the current Route object.
 */
function current_route(): ?Route
{
    return current_route_info()->route;
}

/**
 * Return a class containing information on the current route.
 *
 * @return \Permafrost\CurrentRoute\CurrentRouteInfo
 */
function current_route_info(): CurrentRouteInfo
{
    return CurrentRouteInfo::create();
}

/**
 * Returns the action of the current route.
 */
function current_route_action(): ?string
{
    return current_route_info()->action;
}

/**
 * Returns true if one of the specified wildcard patterns matches the
 * name of the current route.
 *
 * @param array $namePatterns
 *
 * @return mixed
 */
function current_route_matches(array $namePatterns)
{
    return current_route_info()->nameMatches($namePatterns);
}

/**
 * Returns the name of the current route.
 */
function current_route_name(): ?string
{
    return current_route_info()->name;
}

/**
 * Returns true if $name matches the name of the current route.
 *
 * @param string $name
 *
 * @return bool
 */
function current_route_named(string $name): bool
{
    return current_route_info()->named($name);
}
