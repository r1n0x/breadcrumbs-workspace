<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesHolderProvider
{
    public static function create(): VariablesHolder
    {
        return new VariablesHolder();
    }

    /**
     * @param array<int, Variable> $variables
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
