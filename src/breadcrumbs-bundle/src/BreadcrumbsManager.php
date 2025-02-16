<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsManager
{
    public function __construct(
        private readonly ParametersHolder $parametersHolder,
        private readonly VariablesHolder  $variablesHolder
    )
    {
    }

    public function setParameter(string $name, ?string $value, string $routeName = null): static
    {
        $this->parametersHolder->set(new Parameter($name, $value, $routeName));
        return $this;
    }

    public function setVariable(string $name, mixed $value, string $routeName = null): static
    {
        $this->variablesHolder->set(new Variable($name, $value, $routeName));
        return $this;
    }
}