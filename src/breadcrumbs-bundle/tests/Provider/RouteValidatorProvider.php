<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class RouteValidatorProvider
{
    public static function create(): RouteValidator
    {
        return new RouteValidator();
    }
}
