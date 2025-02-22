<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Holder;

use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class ParametersHolder
{
    public const OPTIONAL_PARAMETER = "d3d64c86-cabf-4ff7-978e-b8a99f13f3d1";

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
                    return $parameter->getValue() ?? self::OPTIONAL_PARAMETER;
                }
            }
            return null;
        }
        foreach ($this->parameters as $parameter) {
            if ($parameter->getRouteName() === null && $parameter->getName() === $name) {
                return $parameter->getValue() ?? self::OPTIONAL_PARAMETER;
            }
        }
        return null;
    }
}