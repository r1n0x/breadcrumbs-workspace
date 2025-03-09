<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Resolver\ParametersResolver;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersResolverProvider
{
    public static function create(): ParametersResolver
    {
        return new ParametersResolver();
    }
}
