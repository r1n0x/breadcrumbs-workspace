<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Attribute\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteProvider extends Route
{
    /**
     * @param array<string, string> $defaults
     */
    public static function provide(string $name, string $path, array $defaults = []): Route
    {
        return new Route(
            path: $path,
            name: $name,
            defaults: $defaults
        );
    }
}
