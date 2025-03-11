<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\DefinitionToNodeTransformer;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class DefinitionToNodeTransformerFake
{
    public static function create(): DefinitionToNodeTransformer
    {
        return new DefinitionToNodeTransformer();
    }
}
