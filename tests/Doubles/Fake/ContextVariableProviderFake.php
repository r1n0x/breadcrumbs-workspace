<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextParameterProvider;
use R1n0x\BreadcrumbsBundle\Internal\Provider\ContextVariableProvider;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ContextVariableProviderFake
{
    public static function create(
        VariablesHolder $holder,
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return new ContextVariableProvider($holder, $provider);
    }

    /**
     * @param array<int, Variable> $variables
     *
     * @throws VariableAlreadyDefinedException
     */
    public static function createWithVariables(
        array $variables,
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return self::create(
            VariablesHolderFake::createWithVariables($variables),
            $provider
        );
    }

    public static function createWithParameterProvider(
        ContextParameterProvider $provider
    ): ContextVariableProvider {
        return self::create(
            VariablesHolderFake::create(),
            $provider
        );
    }
}
