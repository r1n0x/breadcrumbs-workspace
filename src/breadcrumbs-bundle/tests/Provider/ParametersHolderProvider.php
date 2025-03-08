<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDocMissingThrowsInspection
 */

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Provider;

use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersHolderProvider
{
    public static function create(): ParametersHolder
    {
        return new ParametersHolder();
    }

    public static function empty(): ParametersHolder
    {
        return self::createWithParameters();
    }

    /**
     * @param array<int, Parameter> $parameters
     */
    public static function createWithParameters(array $parameters = []): ParametersHolder
    {
        $holder = self::create();

        foreach ($parameters as $parameter) {
            $holder->set($parameter);
        }

        return $holder;
    }
}
