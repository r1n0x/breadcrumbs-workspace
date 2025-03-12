<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class NodeSerializerFake
{
    public static function create(): NodeSerializer
    {
        return new NodeSerializer();
    }
}
