<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Event;

use R1n0x\BreadcrumbsBundle\Attribute\Route;

/**
 * @codeCoverageIgnore
 *
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RouteInitializedEvent
{
    public function __construct(
        private string $routeName,
        private Route $route
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
