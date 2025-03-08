<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ContextVariableProviderProvider
{
    public static function create(
        VariablesHolder $holder,
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return new ContextVariableProvider($holder, $provider);
    }

    /**
     * @var array<int, Variable>
     */
    public static function createWithVariables(
        array $variables,
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return self::create(
            VariablesHolderProvider::createWithVariables($variables),
            $provider
        );
    }
}
