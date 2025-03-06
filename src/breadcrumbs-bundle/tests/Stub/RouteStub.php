<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Stub;

use R1n0x\BreadcrumbsBundle\Attribute\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteStub extends Route
{
    /**
     * @param array<string, string> $defaults
     */
    public static function create(string $name, string $path, array $defaults = []): RouteStub
    {
        return new self(
            path: $path,
            name: $name,
            defaults: $defaults
        );
    }
}
