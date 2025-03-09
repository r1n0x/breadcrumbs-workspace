<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
interface RoutesProviderInterface
{
    /**
     * @return array<string, Route>
     */
    public function getRoutes(): array;
}
