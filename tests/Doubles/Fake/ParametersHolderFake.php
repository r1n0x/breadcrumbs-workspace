<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Tests\Doubles\Fake;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ParametersHolderFake
{
    public static function create(): ParametersHolder
    {
        return new ParametersHolder();
    }

    /**
     * @param array<int, Parameter> $parameters
     *
     * @throws ParameterAlreadyDefinedException
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
