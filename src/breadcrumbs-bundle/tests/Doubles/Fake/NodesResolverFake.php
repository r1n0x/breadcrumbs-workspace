<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\CacheReaderInterface;
use R1n0x\BreadcrumbsBundle\Internal\Resolver\NodesResolver;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class NodesResolverFake
{
    public static function create(CacheReaderInterface $cacheReader): NodesResolver
    {
        return new NodesResolver(
            $cacheReader,
            NodeSerializerFake::create(),
            Dummy::string()
        );
    }
}
