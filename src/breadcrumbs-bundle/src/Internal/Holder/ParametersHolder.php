<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Holder;

use R1n0x\BreadcrumbsBundle\Exception\ParameterAlreadyDefinedException;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersHolder
{
    /**
     * @var array<int, Parameter>
     */
    private array $parameters = [];

    /**
     * @throws ParameterAlreadyDefinedException
     */
    public function set(Parameter $parameter): void
    {
        if ($this->has($parameter->getName(), $parameter->getRouteName())) {
            throw new ParameterAlreadyDefinedException($parameter);
        }
        $this->parameters[] = $parameter;
    }

    public function get(string $name, ?string $routeName = null): ?Parameter
    {
        return $this->getParameter($name, $routeName, true);
    }

    private function has(string $name, ?string $routeName = null): bool
    {
        return $this->getParameter($name, $routeName, false) instanceof Parameter;
    }

    private function getParameter(string $name, ?string $routeName, bool $fallback): ?Parameter
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

    private function getScoped(string $name, string $routeName): ?Parameter
    {
        foreach ($this->parameters as $parameter) {
            if ($parameter->getRouteName() === $routeName && $parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }

    private function getGlobal(string $name): ?Parameter
    {
        foreach ($this->parameters as $parameter) {
            if (null === $parameter->getRouteName() && $parameter->getName() === $name) {
                return $parameter;
            }
        }

        return null;
    }
}
