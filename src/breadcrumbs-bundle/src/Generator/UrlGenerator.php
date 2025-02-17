<?php

namespace R1n0x\BreadcrumbsBundle\Generator;

use R1n0x\BreadcrumbsBundle\Holder\ParametersHolder;
use R1n0x\BreadcrumbsBundle\Model\BreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RootBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Model\RouteBreadcrumbDefinition;
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
        if ($definition instanceof RootBreadcrumbDefinition) {
            return null;
        }
        return $this->router->generate($definition->getRouteName(), match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->getParameters($definition),
            default => []
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