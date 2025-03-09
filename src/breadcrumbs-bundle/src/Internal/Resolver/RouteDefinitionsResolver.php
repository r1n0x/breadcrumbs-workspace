<?php

declare(strict_types=1);

namespace R1n0x\BreadcrumbsBundle\Internal\Resolver;

use R1n0x\BreadcrumbsBundle\Attribute\Route;
use R1n0x\BreadcrumbsBundle\Exception\RouteValidationException;
use R1n0x\BreadcrumbsBundle\Exception\VariablesResolverException;
use R1n0x\BreadcrumbsBundle\Internal\Model\RouteBreadcrumbDefinition;
use R1n0x\BreadcrumbsBundle\Internal\Validator\RouteValidator;

/**
 * @author r1n0x <r1n0x-dev@proton.me>
 */
final readonly class RouteDefinitionsResolver
{
    public function __construct(
        private RoutesProviderInterface $provider,
        private VariablesResolver $variablesResolver,
        private ParametersResolver $parametersResolver,
        private RouteValidator $validator,
        private bool $passParametersToExpression
    ) {}

    /**
     * @return array<int, RouteBreadcrumbDefinition>
     *
     * @throws RouteValidationException
     * @throws VariablesResolverException
     */
    public function getDefinitions(): array
    {
        /** @var array<int, RouteBreadcrumbDefinition> $definitions */
        $definitions = [];

        foreach ($this->provider->getRoutes() as $name => $route) {
            $expression = $route->getBreadcrumb()[Route::EXPRESSION] ?? null;
            if (null === $expression) {
                continue;
            }
            $this->validator->validate($route);
            $definitions[] = new RouteBreadcrumbDefinition(
                $name,
                $expression, /* @phpstan-ignore argument.type */
                $route->getBreadcrumb()[Route::PARENT_ROUTE] ?? null, /* @phpstan-ignore argument.type */
                $route->getBreadcrumb()[Route::ROOT] ?? null, /* @phpstan-ignore argument.type */
                $route->getBreadcrumb()[Route::PASS_PARAMETERS_TO_EXPRESSION] ?? $this->passParametersToExpression, /* @phpstan-ignore argument.type */
                $this->parametersResolver->getParameters($route),
                $this->variablesResolver->getVariables($expression) /* @phpstan-ignore argument.type */
            );
        }

        return $definitions;
    }
}
