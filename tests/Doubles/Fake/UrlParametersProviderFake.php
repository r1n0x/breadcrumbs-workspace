<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class UrlParametersProviderFake
{
    public static function create(
        ContextParameterProvider $provider
    ): UrlParametersProvider {
        return new UrlParametersProvider($provider);
    }

    /**
     * @param array<int, Parameter> $parameters
     *
     * @throws ParameterAlreadyDefinedException
     */
    public static function createWithParameters(array $parameters = []): UrlParametersProvider
    {
        return self::create(
            ContextParameterProviderFake::createWithParameters($parameters)
        );
    }
}
