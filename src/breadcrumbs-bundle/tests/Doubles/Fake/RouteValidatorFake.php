<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RouteValidatorFake
{
    public static function create(): RouteValidator
    {
        return new RouteValidator();
    }
}
