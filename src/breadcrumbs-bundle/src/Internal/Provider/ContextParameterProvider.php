<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Provider;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\Parameter;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ContextParameterProvider
{
    public function __construct(
        private ParametersHolder $holder
    ) {}

    /**
     * @throws UndefinedParameterException
     */
    public function get(string $name, ?string $routeName): Parameter
    {
        // get scoped
        if ($this->holder->has($name, $routeName)) {
            /* @phpstan-ignore return.type */
            return $this->holder->get($name, $routeName);
        }

        // get global
        if ($this->holder->has($name)) {
            /* @phpstan-ignore return.type */
            return $this->holder->get($name);
        }

        throw new UndefinedParameterException($name);
    }

    /**
     * @throws UndefinedParameterException
     */
    public function getForDefinition(RouteBreadcrumbDefinition $definition, string $name, ?string $routeName): Parameter
    {
        foreach ($definition->getParameters() as $parameterDefinition) {
            if ($parameterDefinition->getName() === $name) {
                return $this->get($name, $routeName);
            }
        }

        throw new UndefinedParameterException($name);
    }
}
