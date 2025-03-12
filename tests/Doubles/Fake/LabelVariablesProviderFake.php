<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\LabelVariablesProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class LabelVariablesProviderFake
{
    public static function create(
        ContextVariableProvider $provider
    ): LabelVariablesProvider {
        return new LabelVariablesProvider($provider);
    }

    public static function createWithVariables(array $variables = []): LabelVariablesProvider
    {
        return self::create(
            ContextVariableProviderFake::createWithVariables(
                $variables,
                ContextParameterProviderFake::createWithParameters()
            )
        );
    }
}
