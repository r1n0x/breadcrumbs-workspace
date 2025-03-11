<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\RoutesProviderInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final class RoutesProviderStub implements RoutesProviderInterface
{
    /** @var array<string, Route> */
    private array $routes = [];

    public static function create(): RoutesProviderStub
    {
        return new RoutesProviderStub();
    }

    public function addRoute(string $name, string $path, array $breadcrumb): static
    {
        $this->routes[$name] = new Route(path: $path, breadcrumb: $breadcrumb);

        return $this;
    }

    /**
     * @return array<string, Route>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
