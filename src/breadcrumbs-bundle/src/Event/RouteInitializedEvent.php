<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Event;

use R1n0x\BreadcrumbsBundle\Attribute\Route;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteInitializedEvent
{
    public function __construct(
        private readonly string $routeName,
        private readonly Route $route
    ) {}

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }
}
