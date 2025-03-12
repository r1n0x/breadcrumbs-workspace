<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RootsResolverFake
{
    public static function create(array $config): RootsResolver
    {
        return new RootsResolver($config);
    }
}
