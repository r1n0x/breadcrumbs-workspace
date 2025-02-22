<?php

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use R1n0x\BreadcrumbsBundle\Exception\LogicException;
use R1n0x\BreadcrumbsBundle\Internal\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Internal\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
class UrlGenerator
{
    public function __construct(
        private readonly ParametersHolder $holder,
        private readonly RouterInterface  $router
    )
    {
    }

    public function generate(BreadcrumbDefinition $definition): ?string
    {
        if ($definition instanceof RootBreadcrumbDefinition && !$definition->getRouteName()) {
            return null;
        }
        return $this->router->generate($definition->getRouteName(), match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->getParameters($definition),
            $definition instanceof RootBreadcrumbDefinition => [],
            default => throw new LogicException(sprintf('Unexpected breadcrumb type of \'%s\'', get_class($definition)))
        });
    }

    /**
     * @param RouteBreadcrumbDefinition $definition
     * @return array<string, string>
     */
    public function getParameters(RouteBreadcrumbDefinition $definition): array
    {
        $routeName = $definition->getRouteName();
        $parameters = [];
        foreach ($definition->getParameters() as $parameterName) {
            $value = $this->holder->getValue($parameterName, $routeName) ?? $this->holder->getValue($parameterName);
            $parameters[$parameterName] = $value === ParametersHolder::OPTIONAL_PARAMETER ? null : $value;
        }
        return $parameters;
    }
}