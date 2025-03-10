<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class DefinitionToNodeTransformerProvider
{
    public static function create(): DefinitionToNodeTransformer
    {
        return new DefinitionToNodeTransformer();
    }
}
