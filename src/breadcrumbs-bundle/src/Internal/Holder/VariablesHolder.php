<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Holder;

use R1n0x\BreadcrumbsBundle\Exception\VariableAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class VariablesHolder
{
    /**
     * @var array<int, Variable>
     */
    private array $variables = [];

    /**
     * @throws VariableAlreadyDefinedException
     */
    public function set(Variable $variable): void
    {
        if ($this->has($variable->getName(), $variable->getRouteName())) {
            throw new VariableAlreadyDefinedException($variable);
        }
        $this->variables[] = $variable;
    }

    public function get(string $name, ?string $routeName = null): ?Variable
    {
        return $this->getVariable($name, $routeName);
    }

    public function has(string $name, ?string $routeName = null): bool
    {
        return $this->getVariable($name, $routeName) instanceof Variable;
    }

    private function getVariable(string $name, ?string $routeName = null): ?Variable
    {
        if (null !== $routeName) {
            return $this->getGlobal($name, $routeName);
        }

        return $this->getScoped($name);
    }

    private function getGlobal(string $name, string $routeName): ?Variable
    {
        foreach ($this->variables as $variable) {
            if ($variable->getRouteName() === $routeName && $variable->getName() === $name) {
                return $variable;
            }
        }

        return null;
    }

    private function getScoped(string $name): ?Variable
    {
        foreach ($this->variables as $variable) {
            if (null === $variable->getRouteName() && $variable->getName() === $name) {
                return $variable;
            }
        }

        return null;
    }
}
