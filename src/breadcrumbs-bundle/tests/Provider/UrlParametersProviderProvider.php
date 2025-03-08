<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\UrlParametersProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlParametersProviderProvider
{
    public static function create(
        ContextParameterProvider $provider
    ): UrlParametersProvider {
        return new UrlParametersProvider($provider);
    }

    /**
     * @param array<int, Parameter> $parameters
     */
    public static function createWithParameters(array $parameters = []): UrlParametersProvider
    {
        return self::create(
            ContextParameterProviderProvider::createWithParameters($parameters)
        );
    }
}
