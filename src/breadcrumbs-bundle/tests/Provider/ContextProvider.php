<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Context;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ContextProvider extends Route
{
    public static function provide(): Context
    {
        return new Context(new ParametersHolder(), new VariablesHolder());
    }
}
