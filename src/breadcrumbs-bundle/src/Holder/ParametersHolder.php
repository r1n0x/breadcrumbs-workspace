<?php

namespace R1n0x\BreadcrumbsBundle\Holder;

use R1n0x\BreadcrumbsBundle\Model\Parameter;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersHolder
{
    /**
     * @var array<int, Parameter>
     */
    private array $parameters = [];

    public function set(Parameter $parameter): void
    {
        $this->parameters[] = $parameter;
    }

    public function getValue(string $name, string $routeName = null): ?string
    {
        if ($routeName) {
            foreach ($this->parameters as $parameter) {
                if ($parameter->getRouteName() === $routeName && $parameter->getName() === $name) {
                    return $parameter->getValue();
                }
            }
            return null;
        }
        foreach ($this->parameters as $parameter) {
            if ($parameter->getRouteName() === null && $parameter->getName() === $name) {
                return $parameter->getValue();
            }
        }
        return null;
    }
}