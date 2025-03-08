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
        return $this->getVariable($name, $routeName, true);
    }

    private function has(string $name, ?string $routeName = null): bool
    {
        return $this->getVariable($name, $routeName, false) instanceof Variable;
    }

    private function getVariable(string $name, ?string $routeName, bool $fallback): ?Variable
    {
        if (null !== $routeName) {
            $scoped = $this->getScoped($name, $routeName);
            if (null === $scoped && $fallback) {
                return $this->getGlobal($name);
            }

            return $scoped;
        }

        return $this->getGlobal($name);
    }

    private function getScoped(string $name, string $routeName): ?Variable
    {
        foreach ($this->variables as $variable) {
            if ($variable->getRouteName() === $routeName && $variable->getName() === $name) {
                return $variable;
            }
        }

        return null;
    }

    private function getGlobal(string $name): ?Variable
    {
        foreach ($this->variables as $variable) {
            if (null === $variable->getRouteName() && $variable->getName() === $name) {
                return $variable;
            }
        }

        return null;
    }
}
