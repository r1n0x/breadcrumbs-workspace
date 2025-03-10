<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Unused;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodesResolverProvider
{
    public static function create(CacheReaderInterface $cacheReader): NodesResolver
    {
        return new NodesResolver(
            $cacheReader,
            NodeSerializerProvider::create(),
            Unused::string()
        );
    }
}
