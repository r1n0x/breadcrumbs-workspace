<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class LabelVariablesProviderProvider
{
    public static function create(
        ContextVariableProvider $provider
    ): LabelVariablesProvider {
        return new LabelVariablesProvider($provider);
    }

    public static function createWithVariables(array $variables = []): LabelVariablesProvider
    {
        return self::create(
            ContextVariableProviderProvider::createWithVariables(
                $variables,
                ContextParameterProviderProvider::empty()
            )
        );
    }
}
