<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ContextParameterProviderProvider
{
    public static function create(ParametersHolder $holder): ContextParameterProvider
    {
        return new ContextParameterProvider($holder);
    }

    public static function empty(): ContextParameterProvider
    {
        return self::create(ParametersHolderProvider::create());
    }

    /**
     * @param array<int, Parameter> $parameters
     */
    public static function createWithParameters(array $parameters = []): ContextParameterProvider
    {
        return self::create(
            ParametersHolderProvider::createWithParameters($parameters)
        );
    }
}
