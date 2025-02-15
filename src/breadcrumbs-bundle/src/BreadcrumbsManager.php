<?php

namespace R1n0x\BreadcrumbsBundle;

use R1n0x\BreadcrumbsBundle\Storage\ExpressionVariablesStorage;
use R1n0x\BreadcrumbsBundle\Storage\RouterParametersStorage;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class BreadcrumbsManager
{
    public function __construct(
        private readonly RouterParametersStorage $parametersStorage,
        private readonly ExpressionVariablesStorage $variablesStorage
    )
    {
    }


    public function setParameter(string $name, string $value): static
    {
        $this->parametersStorage->set($name, $value);
        return $this;
    }

    public function setVariable(string $name, mixed $value): static
    {
        $this->variablesStorage->set($name, $value);
        return $this;
    }
}