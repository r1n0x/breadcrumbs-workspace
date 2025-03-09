<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\RootsResolver;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RootsResolverProvider
{
    public static function createWithConfig(array $config): RootsResolver
    {
        return new RootsResolver($config);
    }
}
