<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ParametersResolverFake
{
    public static function create(): ParametersResolver
    {
        return new ParametersResolver();
    }
}
