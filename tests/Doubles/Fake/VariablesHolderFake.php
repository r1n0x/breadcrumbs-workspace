<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class VariablesHolderFake
{
    public static function create(): VariablesHolder
    {
        return new VariablesHolder();
    }

    /**
     * @param array<int, Variable> $variables
     *
     * @throws VariableAlreadyDefinedException
     */
    public static function createWithVariables(array $variables = []): VariablesHolder
    {
        $holder = self::create();

        foreach ($variables as $variable) {
            $holder->set($variable);
        }

        return $holder;
    }
}
