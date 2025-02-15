<?php

namespace R1n0x\BreadcrumbsBundle\Holder;

use R1n0x\BreadcrumbsBundle\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesHolder
{
    /**
     * @var array<int, Variable>
     */
    private array $variables = [];

    public function set(Variable $variable): void
    {
        $this->variables[$variable->getName()] = $variable;
    }

    public function getValue(string $name, string $routeName = null): mixed
    {
        if ($routeName) {
            foreach ($this->variables as $variable) {
                if ($variable->getRouteName() === $routeName && $variable->getName() === $name) {
                    return $variable->getValue();
                }
            }
            return null;
        }
        foreach ($this->variables as $variable) {
            if ($variable->getRouteName() === null && $variable->getName() === $name) {
                return $variable->getValue();
            }
        }
        return null;
    }
}