<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Generator;

use Exception;
use R1n0x\BreadcrumbsBundle\Exception\RouteGenerationException;
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
        private readonly RouterInterface $router
    ) {}

    /**
     * @throws RouteGenerationException
     */
    public function generate(BreadcrumbDefinition $definition, ParametersHolder $holder): ?string
    {
        $routeName = $definition->getRouteName();
        if ($definition instanceof RootBreadcrumbDefinition && null === $routeName) {
            return null;
        }

        try {
            return match (true) {
                $definition instanceof RootBreadcrumbDefinition => $this->router->generate($routeName),
                $definition instanceof RouteBreadcrumbDefinition => $this->router->generate(
                    $definition->getRouteName(),
                    $this->getParameters($definition, $holder)
                )
            };
        } catch (Exception $e) {
            throw new RouteGenerationException(previous: $e);
        }
    }

    /**
     * @return array<string, null|string>
     */
    public function getParameters(RouteBreadcrumbDefinition $definition, ParametersHolder $holder): array
    {
        $routeName = $definition->getRouteName();
        $parameters = [];
        foreach ($definition->getParameters() as $parameterName) {
            $value = $holder->getValue($parameterName, $routeName) ?? $holder->getValue($parameterName);
            $parameters[$parameterName] = ParametersHolder::OPTIONAL_PARAMETER === $value ? null : $value;
        }

        return $parameters;
    }
}
