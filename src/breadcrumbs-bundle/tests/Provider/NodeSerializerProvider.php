<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\NodeSerializer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class NodeSerializerProvider
{
    public static function create(): NodeSerializer
    {
        return new NodeSerializer();
    }
}
