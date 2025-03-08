<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Provider;

use R1n0x\BreadcrumbsBundle\Exception\UndefinedParameterException;
use R1n0x\BreadcrumbsBundle\Exception\UndefinedVariableException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\VariablesHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\Variable;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class ContextVariableProvider
{
    public function __construct(
        private VariablesHolder $holder,
        private ContextParameterProvider $provider
    ) {}

    /**
     * @throws UndefinedVariableException
     */
    public function get(BreadcrumbDefinition $definition, string $name, ?string $routeName): Variable
    {
        $variable = $this->holder->get($name, $routeName);
        if (null !== $variable) {
            return $variable;
        }

        if ($definition instanceof RouteBreadcrumbDefinition && $definition->getPassParametersToExpression()) {
            try {
                $parameter = $this->provider->getForDefinition($definition, $name, $routeName);

                return new Variable(
                    $name,
                    $parameter->getAutowiredValue(),
                    $routeName
                );
            } catch (UndefinedParameterException) {
            }
        }

        throw new UndefinedVariableException($name);
    }
}
