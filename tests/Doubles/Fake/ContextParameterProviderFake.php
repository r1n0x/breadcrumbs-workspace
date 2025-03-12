<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ContextParameterProviderFake
{
    public static function create(ParametersHolder $holder): ContextParameterProvider
    {
        return new ContextParameterProvider($holder);
    }

    /**
     * @param array<int, Parameter> $parameters
     *
     * @throws ParameterAlreadyDefinedException
     */
    public static function createWithParameters(array $parameters = []): ContextParameterProvider
    {
        return self::create(
            ParametersHolderFake::createWithParameters($parameters)
        );
    }
}
