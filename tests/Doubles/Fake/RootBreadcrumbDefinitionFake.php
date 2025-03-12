<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Tests\Doubles\Dummy;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RootBreadcrumbDefinitionFake
{
    public static function create(): RootBreadcrumbDefinition
    {
        return new RootBreadcrumbDefinition(
            Dummy::string(),
            Dummy::string(),
            Dummy::string()
        );
    }
}
