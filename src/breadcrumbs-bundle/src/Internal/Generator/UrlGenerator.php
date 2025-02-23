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
        private readonly RouterInterface $router
    ) {}

    public function generate(BreadcrumbDefinition $definition): ?string
    {
        $routeName = $definition->getRouteName();
        if ($definition instanceof RootBreadcrumbDefinition && !$routeName) {
            return null;
        }

        /* @phpstan-ignore argument.type */
        return $this->router->generate($routeName, match (true) {
            $definition instanceof RouteBreadcrumbDefinition => $this->getParameters($definition),
            $definition instanceof RootBreadcrumbDefinition => [],
            default => throw new LogicException(sprintf('Unexpected breadcrumb type of \'%s\'', get_class($definition)))
        });
    }

    /**
     * @return array<string, null|string>
     */
    public function getParameters(RouteBreadcrumbDefinition $definition): array
    {
        $routeName = $definition->getRouteName();
        $parameters = [];
        foreach ($definition->getParameters() as $parameterName) {
            $value = $this->holder->getValue($parameterName, $routeName) ?? $this->holder->getValue($parameterName);
            $parameters[$parameterName] = ParametersHolder::OPTIONAL_PARAMETER === $value ? null : $value;
        }

        return $parameters;
    }
}
