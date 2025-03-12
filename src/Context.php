<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class Context
{
    public function __construct(
        private ParametersHolder $parametersHolder,
        private VariablesHolder $variablesHolder
    ) {}

    /**
     * @throws ParameterAlreadyDefinedException
     */
    public function setParameter(
        string $name,
        null|int|string $value,
        ?string $routeName = null
    ): Context {
        $this->parametersHolder->set(new Parameter($name, $routeName, $value, null));

        return $this;
    }

    /**
     * @throws VariableAlreadyDefinedException
     */
    public function setVariable(
        string $name,
        mixed $value,
        ?string $routeName = null
    ): Context {
        $this->variablesHolder->set(new Variable($name, $value, $routeName));

        return $this;
    }
}
