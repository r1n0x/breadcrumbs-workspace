<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Holder;

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

    public function set(Parameter $parameter): void
    {
        $this->parameters[] = $parameter;
    }

    public function getValue(string $name, ?string $routeName = null): null|int|string
    {
        if (null !== $routeName) {
            foreach ($this->parameters as $parameter) {
                if ($parameter->getRouteName() === $routeName && $parameter->getName() === $name) {
                    return $parameter->getValue() ?? null;
                }
            }

            return null;
        }
        foreach ($this->parameters as $parameter) {
            if (null === $parameter->getRouteName() && $parameter->getName() === $name) {
                return $parameter->getValue() ?? null;
            }
        }

        return null;
    }
}
